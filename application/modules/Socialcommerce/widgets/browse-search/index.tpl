
<?php
/* Include the common user-end field switching javascript */
echo $this->partial('_jsSwitch.tpl', 'fields', array(
    'topLevelId' => (int) $this->topLevelId,
    'topLevelValue' => (int) $this->topLevelValue
))
?>

<?php
echo $this->form
  ->setAction($this->url(array(), 'user_general', true))
  ->render($this);
?>

<script type="text/javascript">
    en4.core.runonce.add(function () {
        var formElement = $$('.layout_socialcommerce_browse_search .field_search_criteria')[0];
        // On search
        formElement.addEvent('submit', function (event) {
            if (!window.searchMembers) {
                return;
            }
            event.stop();
            searchMembers();
        });

        window.addEvent('onChangeFields', function () {
            var firstSep = $$('li.browse-separator-wrapper')[0];
            var lastSep;
            var nextEl = firstSep;
            var allHidden = true;
            do {
                nextEl = nextEl.getNext();
                if (nextEl.get('class') == 'browse-separator-wrapper') {
                    lastSep = nextEl;
                    nextEl = false;
                } else {
                    allHidden = allHidden && (nextEl.getStyle('display') == 'none');
                }
            } while (nextEl);
            if (lastSep) {
                lastSep.setStyle('display', (allHidden ? 'none' : ''));
            }
        });
    });
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
