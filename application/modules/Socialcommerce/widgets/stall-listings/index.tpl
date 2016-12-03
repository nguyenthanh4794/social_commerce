
<?php
    echo $this->partial('_list_most_item.tpl', 'socialcommerce', array('stalls' => $this->paginator, 'tab' => 'stalls_browse_listing'));
?>
