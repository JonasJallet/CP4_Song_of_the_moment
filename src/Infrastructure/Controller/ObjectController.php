<?php

namespace Controller;

use App\Application\Command\Orders\UpdateOrder\UpdateOrder;
use App\Application\Command\UpdateDomainObject\UpdateDomainObject;
use App\Application\Query\GetObjectById\GetObjectById;
use App\Infrastructure\Utils\ParticularReturns;
use App\Infrastructure\Validator\Api\OrderValidator;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class ObjectController
{
    public function __construct(
        private MessageBusInterface $queryBus,
        private MessageBusInterface $commandBus,
    )
    {
    }
    #[Route("/object/{objectId", name: "get_object", methods: ["GET"])]
    public function getObject(int $objectId,Request $request): JsonResponse
    {
        try {
            $getObject = new GetObjectById();
            $getObject->objectId = $objectId;
            $result = $this->queryBus->dispatch($getObject);
            $object = $result->last(HandledStamp::class)->getResult();
            return new JsonResponse($object, 200);
        } catch (\Exception $exception) {
            $exception->getCode() === 0 || $exception->getCode() > 600 ? $code = 400 : $code = $exception->getCode();
            return new JsonResponse(
                [
                    'error' => $exception->getMessage(),
                ], $code, ['Content-Type', 'application/json']
            );
        }
    }

    #[Route("/object/{objectId}", name: "update_object", methods: ["PATCH"])]
    public function updateObject(int $objectId,Request $request): JsonResponse
    {
        try {
            $fields = json_decode($request->getContent(), true);
            $updateObject = new UpdateDomainObject();
            $updateObject->id = $objectId;
            $updateObject->fields = $fields;
            $this->commandBus->dispatch($updateObject);

            return new JsonResponse(
                [
                    "message" => "The object $objectId is updated"
                ], 200);
        } catch (\Exception $exception) {
            $exception->getCode() === 0 || $exception->getCode() > 600 ? $code = 400 : $code = $exception->getCode();
            return new JsonResponse(
                [
                    'error' => $exception->getMessage(),
                ], $code, ['Content-Type', 'application/json']
            );
        }
    }
}