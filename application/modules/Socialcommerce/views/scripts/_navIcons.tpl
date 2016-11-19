<ul class="socialcommerce_seller_menu">
    <?php foreach( $this->container as $link ): ?>
    <li class="<?php echo $link->getClass() ? ' ' . $link->getClass() : ''; echo $link->isActive() ? ' active' : '' ?>">
        <?php echo $this->htmlLink($link->getHref(), $this->translate('<i class="'. $link->get('icon-class') .'"></i>'.$link->getLabel()), array(
        'class' => 'buttonlink',
        'target' => $link->get('target'),
        )) ?>
    </li>
    <?php endforeach; ?>
</ul>