<?php

namespace App\Infrastructure\Persistence\Fixtures;

use App\Infrastructure\Persistence\Entity\Song;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SongFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $songs = [
            [
                'title' => 'Je fume pu d\'shit',
                'artist' => 'Stupeflip',
                'album' => 'Stupeflip',
                'photo_album' => 'https://m.media-amazon.com/images/I/61PF6XQWF1L.jpg',
                'link_youtube' => 'ZYEIFIZZXTs',
            ],
            [
                'title' => 'Peng Black Girls Remix',
                'artist' => 'Enny ft. Jorja Smith',
                'album' => 'Be Right Back',
                'photo_album' => 'https://images-prod.dazeddigital.com/786/azure/dazed-prod/1310/3/1313229.jpg',
                'link_youtube' => 'VW_UHYs3giU',
            ],
            [
                'title' => 'Golden Dawn',
                'artist' => 'Goat',
                'album' => 'World music',
                'photo_album' => 'http://image.noelshack.com/fichiers/2023/05/4/1675296845-aa.jpeg',
                'link_youtube' => 'u3OKLIBnZJY',
            ],
            [
                'title' => 'C\'era Una Volta',
                'artist' => 'Nôze',
                'album' => 'Dring',
                'photo_album' => 'http://image.noelshack.com/fichiers/2023/05/4/1675296941-bb.jpeg',
                'link_youtube' => 'ldrYzl4nn-g',
            ],
            [
                'title' => 'Acetate',
                'artist' => 'Roger Molls',
                'album' => 'Metamorphosis of Muses',
                'photo_album' => 'https://m.media-amazon.com/images/I/51m2vZVmDAL._UXNaN_FMjpg_QL85_.jpg',
                'link_youtube' => 'IQkZ8nXUVGw',
            ],
            [
                'title' => 'Double Trouble',
                'artist' => 'Yarah bravo',
                'album' => 'Good girls Rarely make history',
                'photo_album' => 'https://f4.bcbits.com/img/a0296267368_5.jpg',
                'link_youtube' => '7D3W8BypRTw',
            ],
            [
                'title' => 'Relax feat ASM',
                'artist' => 'La Fine Equipe & Mattic',
                'album' => 'Fantastic Planet',
                'photo_album' => 'https://m.media-amazon.com/images/I/519Bdt461oL._UXNaN_FMjpg_QL85_.jpg',
                'link_youtube' => 'GjTey__ass8',
            ],
            [
                'title' => 'Oh yeah baby',
                'artist' => 'Robert le magnifique',
                'album' => 'Oh yeah baby',
                'photo_album' => 'https://m.media-amazon.com/images/I/51U6S20NN9L.jpg',
                'link_youtube' => '5d5tMeS4UQk',
            ],
            [
                'title' => 'Enième Lune',
                'artist' => 'Nemo Nebbia',
                'album' => 'En direct du brouillard',
                'photo_album' => 'https://www.abcdrduson.com/wp-content/uploads/2014/06/a3458999160_10-1024x1012.jpg',
                'link_youtube' => 'IZJQ5ApWCpg',
            ],
            [
                'title' => 'Sleeping Ute',
                'artist' => 'Grizzly bear',
                'album' => 'Shields',
                'photo_album' => 'http://image.noelshack.com/fichiers/2023/05/4/1675296984-cc.gif',
                'link_youtube' => '0tCELpi_43g',
            ],
            [
                'title' => 'Niger Oil',
                'artist' => 'Al Quetz',
                'album' => 'Drums Come From Africa',
                'photo_album' => 'https://f4.bcbits.com/img/a0191801240_65',
                'link_youtube' => '2oGtJh_LOrk',
            ],
            [
                'title' => 'Mala Vida',
                'artist' => 'Mano Negra',
                'album' => 'Patchanka',
                'photo_album' => 'https://m.media-amazon.com/images/I/A1LqlcGmqnL._SL1500_.jpg',
                'link_youtube' => 'z0pSU8xQFN8',
            ],
            [
                'title' => 'My Sound',
                'artist' => 'Skarra Mucci',
                'album' => 'Greater Than Great',
                'photo_album' => 'https://m.media-amazon.com/images/I/51MLDP8tZuL._UXNaN_FMjpg_QL85_.jpg',
                'link_youtube' => 'df4YO3LNjbw',
            ],
            [
                'title' => 'Paralized',
                'artist' => 'John and the Volta',
                'album' => 'Empirical EP Extended',
                'photo_album' => 'http://www.phonographecorp.com/wp-content/uploads/2013/10/Miscwebfront.jpg',
                'link_youtube' => 'ugbq_zN7qsY',
            ],
            [
                'title' => 'Enter Sandman',
                'artist' => 'Metallica',
                'album' => 'Metallica',
                'photo_album' => 'https://upload.wikimedia.org/wikipedia/fr/d/de/Metallica_%28album%29.jpg',
                'link_youtube' => 'CD-E-LDc384',
            ],
            [
                'title' => 'What You Want',
                'artist' => 'Boys Noize',
                'album' => 'Out of the black',
                'photo_album' => 'https://static.qobuz.com/images/covers/25/51/0887158205125_600.jpg',
                'link_youtube' => 'OxJy92awED0',
            ],
            [
                'title' => 'Paranoid',
                'artist' => 'Black Sabbath',
                'album' => 'Paranoid',
                'photo_album' => 'https://m.media-amazon.com/images/I/61EvGaXSOVL._SL1400_.jpg',
                'link_youtube' => '0qanF-91aJo',
            ],
            [
                'title' => 'Le Bruit des Portes',
                'artist' => 'Cabadzi',
                'album' => 'Des angles et des épines',
                'photo_album' => 'https://m.media-amazon.com/images/I/614M8hMtCAL._SS500_.jpg',
                'link_youtube' => 'IHpR0sP5xXo',
            ],
            [
                'title' => 'Dance Me',
                'artist' => 'Soul Revolution',
                'album' => 'One More Time',
                'photo_album' => 'http://image.noelshack.com/fichiers/2023/05/4/1675297261-dd.jpeg',
                'link_youtube' => 'H7-kzePu5eo',
            ],
            [
                'title' => 'Riot Radio',
                'artist' => 'The Dead 60s',
                'album' => 'The Dead 60s',
                'photo_album' => 'https://akamai.sscdn.co/uploadfile/letras/albuns/d/b/9/0/5323.jpg',
                'link_youtube' => 'yZRbMzF-KuQ',
            ],
            [
                'title' => 'In The Woods',
                'artist' => 'Hugo Kant',
                'album' => 'Leave Me Alone EP',
                'photo_album' => 'http://image.noelshack.com/fichiers/2023/05/3/1675290543-1200x1200bf-60.jpg',
                'link_youtube' => 'lGe7HvjMcOQ',
            ],
            [
                'title' => 'I Follow Rivers',
                'artist' => 'Lykke Li ',
                'album' => 'Wounded Rhymes',
                'photo_album' => 'https://m.media-amazon.com/images/I/51KZ-ocYLsL.jpg',
                'link_youtube' => '94Hz2TEWW18',
            ],
            [
                'title' => 'Dans le club',
                'artist' => 'TTC',
                'album' => 'Bâtards sensibles',
                'photo_album' => 'https://static.qobuz.com/images/covers/15/94/5051083019415_600.jpg',
                'link_youtube' => 'udjMEqeijJA',
            ],
            [
                'title' => 'Misty Moon',
                'artist' => 'Fingers and Cream',
                'album' => 'Out in a Blue Sky',
                'photo_album' => 'https://i.scdn.co/image/ab67616d0000b273c1ec84134438c41e741b9d7b',
                'link_youtube' => 'LkP15zWb0VY',
            ]
        ];

        foreach ($songs as $key => $song) {
            $newSong = new Song();
            $newSong->setTitle($song['title']);
            $newSong->setArtist($song['artist']);
            $newSong->setAlbum($song['album']);
            $newSong->setPhotoAlbum($song['photo_album']);
            $newSong->setLinkYoutube($song['link_youtube']);
            $manager->persist($newSong);
            $this->addReference('song_' . $key, $newSong);
        }

        $manager->flush();
    }
}
