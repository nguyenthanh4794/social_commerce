<?php
echo $this->form ->render($this);
?>

<script type="text/javascript">
    function initialize() {
        var input = /** @type {HTMLInputElement} */(
                document.getElementById('location'));

        var autocomplete = new google.maps.places.Autocomplete(input);

        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                return;
            }

            document.getElementById('lat').value = place.geometry.location.lat();
            document.getElementById('long').value = place.geometry.location.lng();
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

    var getCurrentLocation = function(obj)
    {
        if(navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                if(pos)
                {
                    var current_posstion = new Request.JSON({
                        'format' : 'json',
                        'url' : '<?php echo $this->url(array('action'=>'get-my-location', 'controller' => 'index'), 'socialcommerce_general') ?>',
                        'data' : {
                            latitude : pos.lat(),
                            longitude : pos.lng(),
                        },
                        'onSuccess' : function(json, text) {

                            if(json.status == 'OK')
                            {
                                document.getElementById('location').value = json.results[0].formatted_address;
                                document.getElementById('lat').value = json.results[0].geometry.location.lat;
                                document.getElementById('long').value = json.results[0].geometry.location.lng;
                            }
                            else{
                                handleNoGeolocation(true);
                            }
                        }
                    });
                    current_posstion.send();
                }

            }, function() {
                handleNoGeolocation(true);
            });
        }
        else {
            // Browser doesn't support Geolocation
            handleNoGeolocation(false);
        }
        return false;
    }

    function handleNoGeolocation(errorFlag) {
        if (errorFlag) {
            document.getElementById('location').value = 'Error: The Geolocation service failed.';
        }
        else {
            document.getElementById('location').value = 'Error: Your browser doesn\'t support geolocation.';
        }
    }
</script>
