/* eslint-disable indent */
var youTubePlayer;

var YT;

function onYouTubeIframeAPIReady()
{
    'use strict';

    var inputVideoId = document.getElementById('YouTube-video-id');
    var videoId = inputVideoId.value;

    function onError(event)
    {
        youTubePlayer.personalPlayer.errors.push(event.data);
        youTubePlayer.playVideo();
        youTubePlayer.unMute();

        // Check if YouTube error is 150
        if (event.data === 150) {
            document.getElementById("next").click();
        }
    }

    function onPlayerReady(event)
    {
        youTubePlayer.playVideo();
        youTubePlayer.unMute();
    }

    function onStateChange(event)
    {
        let playButton = document.getElementById('play');
        let pauseButton = document.getElementById('pause');

        switch (event.data) {
            case YT.PlayerState.CUED:
                youTubePlayer.playVideo();
                youTubePlayer.unMute();
                break;
            case YT.PlayerState.ENDED:
                if (currentRow !== songRows.length - 1) {
                    let nextButton = document.getElementById("next");
                    if (nextButton) {
                        nextButton.click();
                    }
                }
                break;
            case YT.PlayerState.PAUSED:
                playButton.classList.remove("d-none"); // Show play button
                pauseButton.classList.add("d-none"); // Hide pause button
                break;
            case YT.PlayerState.PLAYING:
                playButton.classList.add("d-none");
                pauseButton.classList.remove("d-none"); // Show pause button
                break;
            default:
                break;
        }
    }

    youTubePlayer = new YT.Player(
        'YouTube-player',
        {
            videoId: videoId,
            height: 1,
            width: 1,
            playerVars: {
                'autohide': 0,
                'cc_load_policy': 0,
                'controls': 1,
                'disablekb': 1,
                'iv_load_policy': 3,
                'modestbranding': 1,
                'rel': 0,
                'showinfo': 0
            },
            events: {
                'onError': onError,
                'onReady': onPlayerReady,
                'onStateChange': onStateChange
            }
        }
    );

    youTubePlayer.personalPlayer = {
        'currentTimeSliding': false,
        'errors': []
    };
}

function youTubePlayerActive()
{
    'use strict';
    return youTubePlayer;
}

const songRows = document.querySelectorAll('.song-on-playlist');
let currentRow = 0;

if (songRows.length > 0) {
    songRows.forEach((row, index) => {
        let youtube = row.getAttribute('data-youtube');
        let photo = row.getAttribute('data-photo');
        let title = row.getAttribute('data-title');
        let artist = row.getAttribute('data-artist');
        row.addEventListener('click', () => {
            document.getElementById('YouTube-video-id').value = youtube;
            document.getElementById('Album-photo-id').src = photo;
            document.getElementById('Infos-id').innerHTML = title + ' - ' + artist;
            youTubePlayerChangeVideoId();
            currentRow = index;
        });
    });

    document.getElementById('next').addEventListener('click', () => {
        currentRow = (currentRow + 1) % songRows.length;
        let nextRow = songRows[currentRow];
        let youtube = nextRow.getAttribute('data-youtube');
        let photo = nextRow.getAttribute('data-photo');
        let title = nextRow.getAttribute('data-title');
        let artist = nextRow.getAttribute('data-artist');
        document.getElementById('YouTube-video-id').value = youtube;
        document.getElementById('Album-photo-id').src = photo;
        document.getElementById('Infos-id').innerHTML = title + ' - ' + artist;
        youTubePlayerChangeVideoId();
        console.log (currentRow);
        console.log (songRows.length);
    });
}

/**
 * Get videoId from the #YouTube-video-id HTML item value, load this video, pause it and show new infos.
 */
function youTubePlayerChangeVideoId()
{
    'use strict';

    var inputVideoId = document.getElementById('YouTube-video-id');
    var videoId = inputVideoId.value;

    youTubePlayer.cueVideoById({
        videoId: videoId
    });

    youTubePlayer.playVideo();
    youTubePlayer.unMute();
}

/**
 * Seek the video to the currentTime.
 * param currentTime: 0 <= number <= 100
 */
function youTubePlayerCurrentTimeChange(currentTime)
{
    'use strict';

    youTubePlayer.personalPlayer.currentTimeSliding = false;
    if (youTubePlayerActive()) {
        youTubePlayer.seekTo(currentTime * youTubePlayer.getDuration() / 100, true);
    }
}

/**
 * Mark that the HTML slider move.
 */
function youTubePlayerCurrentTimeSlide()
{
    'use strict';

    youTubePlayer.personalPlayer.currentTimeSliding = true;
}

/**
 *   Display some video info to #YouTube-player-infos,
 *   errors to #YouTube-player-errors and set progress bar #YouTube-player-progress.
 */
function youTubePlayerDisplayInfos()
{
    'use strict';

    if ((this.nbCalls === undefined) || (this.nbCalls >= 3)) {
        this.nbCalls = 0;
    } else {
        ++this.nbCalls;
    }

    if (youTubePlayerActive()) {
        var current = youTubePlayer.getCurrentTime();
        var duration = youTubePlayer.getDuration();
        var currentPercent = (current && duration
            ? current * 100 / duration
            : 0);

        if (!current) {
            current = 0;
        }
        if (!duration) {
            duration = 0;
        }

        if (!youTubePlayer.personalPlayer.currentTimeSliding) {
            document.getElementById('YouTube-player-progress').value = currentPercent;
        }

        document.getElementById('YouTube-player-infos').innerHTML = (
            '<strong>' + current.toFixed(2) + '</strong>/<strong>' + duration.toFixed(2) + '</strong>s = <strong>' + currentPercent.toFixed(2) + '</strong>%<br>'
        );

        document.getElementById('YouTube-player-errors').innerHTML = '<div>Errors: <strong>' + youTubePlayer.personalPlayer.errors + '</strong></div>';
    }
}

/**
 * Pause.
 */
let pause = document.getElementById('pause');

pause.addEventListener("click", function () {
    youTubePlayerPause();
});

function youTubePlayerPause()
{
    'use strict';
    youTubePlayer.pauseVideo();
}

/**
 * Play.
 */
let play = document.getElementById('play');

play.addEventListener("click", function () {
    youTubePlayerPlay();
});

function youTubePlayerPlay()
{
    'use strict';
    youTubePlayer.playVideo();
}

/**
 * Stop.
 */
let stop = document.getElementById('stop');

stop.addEventListener("click", function () {
    youTubePlayerStop();
});

function youTubePlayerStop()
{
    'use strict';

    if (youTubePlayerActive()) {
        youTubePlayer.seekTo(0);
        youTubePlayer.pauseVideo();

    }
}

/**
 * Main
 */
(function () {
    'use strict';

    function init()
    {
        // Load YouTube library
        var tag = document.createElement('script');

        tag.src = 'https://www.youtube.com/iframe_api';

        var first_script_tag = document.getElementsByTagName('script')[0];

        first_script_tag.parentNode.insertBefore(tag, first_script_tag);

        // Set timer to display infos
        setInterval(youTubePlayerDisplayInfos, 1000);
    }

    if (window.addEventListener) {
        window.addEventListener('load', init);
    } else if (window.attachEvent) {
        window.attachEvent('onload', init);
    }
}());
