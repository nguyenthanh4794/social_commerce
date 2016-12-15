<div id="<?php echo $this -> id ?>-wrapper" class="form-wrapper">
    <div id="<?php echo $this -> id ?>-label" class="form-label">
        <label for="<?php echo $this -> id ?>" class="<?php echo $this -> params['required'] ? 'required':'optional' ?>">
            <?php echo $this -> params['label']?>
        </label>
    </div>
    <div id="<?php echo $this -> id ?>-element" class="form-element">
        <input type="hidden" name="<?php echo $this -> id ?>" id="<?php echo $this -> id ?>" value="<?php echo $this -> params['value'] ? $this -> params['value'] : 0 ?>">
        <div style="<?php echo $this -> params['style'] ?>" id="<?php echo $this -> id ?>" class="yndform_rating" onmouseout="rating_out();">
            <span id="rate_1" class="ynicon yn-star" onclick="rate(1);" onmouseover="rating_over(1);"></span>
            <span id="rate_2" class="ynicon yn-star" onclick="rate(2);" onmouseover="rating_over(2);"></span>
            <span id="rate_3" class="ynicon yn-star" onclick="rate(3);" onmouseover="rating_over(3);"></span>
            <span id="rate_4" class="ynicon yn-star" onclick="rate(4);" onmouseover="rating_over(4);"></span>
            <span id="rate_5" class="ynicon yn-star" onclick="rate(5);" onmouseover="rating_over(5);"></span>
            <span id="rating_text" class="yndform_rating_decs"></span>
        </div>
    </div>
</div>

<style>
    .yndform_rating{
        padding: 0 1px;
    }
    .yndform_rating span.ynicon {
        padding: 0 2px;
        font-size: 24px;
        font-weight: normal;
        color: #ccc;
        cursor: pointer;
        display: inline-block;
        vertical-align: bottom;
        margin: 0 -3px;
    }
    .yndform_rating span.ynicon.rating {
        color: #ffa800;
    }
</style>

<script type="text/javascript">
    en4.core.runonce.add(function() {
        var pre_rate = $('<?php echo $this -> id ?>').value;

        var rating_over = window.rating_over = function(rating) {
            $('rating_text').innerHTML = "Click to rate";
            for(var x=1; x<=5; x++) {
                if(x <= rating) {
                    $('rate_'+x).set('class', 'ynicon yn-star rating');
                } else {
                    $('rate_'+x).set('class', 'ynicon yn-star');
                }
            }
        }
        var rating_out = window.rating_out = function() {
            $('rating_text').innerHTML = "";
            if (pre_rate != 0){
                set_rating();
            }
            else {
                for(var x=1; x<=5; x++) {
                    $('rate_'+x).set('class', 'ynicon yn-star');
                }
            }
        }
        var set_rating = window.set_rating = function() {
            var rating = pre_rate;
            for(var x=1; x<=parseInt(rating); x++) {
                $('rate_'+x).set('class', 'ynicon yn-star rating');
            }

            for(var x=parseInt(rating)+1; x<=5; x++) {
                $('rate_'+x).set('class', 'ynicon yn-star');
            }
        }
        var rate = window.rate = function(rating) {
            pre_rate = rating;
            set_rating();
            $('<?php echo $this -> id ?>').value = rating;
            $('<?php echo $this -> id ?>').fireEvent('change');
        }
        set_rating();
    });
</script>