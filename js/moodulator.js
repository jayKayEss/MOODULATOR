Moodulator = {

    seasons: ['winter', 'spring', 'summer', 'fall'],

    counts: {},

    artists: {},

    needsRefresh: {
        winter: false,
        spring: false,
        summer: false,
        fall: false
    },

    nextPageTimeout: null,

    populate: function(page){
        $.ajax({
            url: "srv/get_artists.php",
            data: {
                lastfm_name: 'jaykayess',
                page: page
            },
            success: function(data){
                Moodulator.populateArtistResults(data);
           }
        });

        Moodulator.renderArtistResults();
        nextPageTimeout = setTimeout(function(){
            Moodulator.populate(page+1);
        }, 1500);
     },

    populateArtistResults: function(data) {
        for(var i in Moodulator.seasons) {
            var season = Moodulator.seasons[i];

            if (!Moodulator.counts[season]) {
                Moodulator.counts[season] = {};
            }

            for (var artist_id in data[season]) {
                var artist = data[season][artist_id];

                if (!Moodulator.artists[artist_id]) {
                    Moodulator.artists[artist_id] = artist;
                }

                if (!Moodulator.counts[season][artist_id]) {
                    Moodulator.counts[season][artist_id] = artist.count;
                } else {
                    Moodulator.counts[season][artist_id] = 
                        Moodulator.counts[season][artist_id] + artist.count;
                }

                var new_count = Moodulator.counts[season][artist_id];
                var artist_main = Moodulator.artists[artist_id];
                if (new_count > artist_main.count) {
                    artist_main.count = artist.count;

                    if (artist_main.season != season) {
                        Moodulator.needsRefresh[artist_main.season] = true;
                        artist_main.season = season;
                    }
                }

                Moodulator.needsRefresh[season] = true;
            }
        }
    },

    renderArtistResults: function() {
        for (var i in Moodulator.seasons) {
            var season = Moodulator.seasons[i];
            if (!Moodulator.needsRefresh[season]) continue;
            Moodulator.needsRefresh[season] = false;

            var display = $('#'+season+'-display');
            var holding = $('#'+season+'-holding');
            holding.empty();

            var data = [];
            for (mbid in Moodulator.counts[season]) {
                data.push([mbid, Moodulator.counts[season][mbid]]);
            }

            data.sort(function(a,b){
                return b[1] - a[1];
            });

            var len = 0;
            for (var y in data) {
                var mbid = data[y][0];
                var count = data[y][1];
                var artist = Moodulator.artists[mbid];

                if (artist.season != undefined &&
                    artist.season != season) continue;

                holding.append(
                    '<li data-id="'+mbid+'">'+
                    '<img src="'+artist.image+'" />'+
                    '</li>'
                );
                len = len + 1;
                if (len >= 15) break;
            }

            $('#'+season+'-display').quicksand($('#'+season+'-holding li'));
        }
    }
};

$('document').ready(function(){
    Moodulator.populate(1);
});

