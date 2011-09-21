Moodulator = {

    seasons: ['winter', 'spring', 'summer', 'fall'],

    counts: {},

    artists: {},

    tags: {},

    needsRefresh: {
        winter: false,
        spring: false,
        summer: false,
        fall: false
    },

    nextPageTimeout: null,

    populate: function(page, username){
        $.ajax({
            url: "../srv/get_artists.php",
            data: {
                username: username,
                page: page
            },
            success: function(data){
                Moodulator.populateArtistResults(data);
           }
        });

        Moodulator.renderArtistResults();
        nextPageTimeout = setTimeout(function(){
            Moodulator.populate(page+1, username);
        }, 1500);
     },

    populateArtistResults: function(data) {
        for(var i in Moodulator.seasons) {
            var season = Moodulator.seasons[i];

            if (!Moodulator.counts[season]) {
                Moodulator.counts[season] = {};
            }

            if (!Moodulator.tags[season]) {
                Moodulator.tags[season] = {};
            }

            for (var artist_id in data[season]) {
                if (artist_id == undefined) continue;
                var artist = data[season][artist_id];
                if (artist.image == undefined) continue;

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

                console.log(artist.tags);
                for (var x in artist.tags.tag) {
                    var tag = artist.tags.tag[x];

                    if (!Moodulator.tags[season][tag.name]) {
                        Moodulator.tags[season][tag.name] =
                            Moodulator.tags[season][tag.name] + artist.count;
                    } else {
                        Moodulator.tags[season][tag.name] = 1;
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

            var display_tags = $('#'+season+'-tags-display');
            var holding_tags = $('#'+season+'-tags-holding');
            holding_tags.empty();

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

                if (!artist.image) continue;

                holding.append(
                    '<li data-id="'+mbid+'">'+
                    '<img src="'+artist.image+'" title="'+artist.name+'" />'+
                    '</li>'
                );
                len = len + 1;
                if (len >= 15) break;
            }

            var tag_data = [];
            for (tag in Moodulator.tags[season]) {
                tag_data.push([tag, Moodulator.tags[season]]);
            }

            tag_data.sort(function(a,b){
                return b[1] - a[1];
            });

            len = 0;
            for (var y in tag_data) {
                var tag = tag_data[y][0];
                var count = tag_data[y][1];
                var id = tag.replace(/\s/g, '_');
                
                holding_tags.append(
                    '<li data-id="'+season+'-'+id+'">'+
                    tag+'</li>'
                );

                len = len + 1;
                if (len >= 12) break;
            }
            
            $('#'+season+'-display').quicksand($('#'+season+'-holding li'));
            $('#'+season+'-tags-display').quicksand($('#'+season+'-tags-holding li'));

        }
    }
};

$('document').ready(function(){
    var regex = /\/(\w+)$/;
    var match = regex.exec(window.location);
    console.log(match);
    if (match) {
        $('img').tooltip();
        Moodulator.populate(1, match[1]);
    } else {
        $('#form form').submit(function(){
            var user = $('#form input').val();
            window.location = window.location + 'display/' + user;
            return false;
        });
    }
});

