<?php if (!empty($pagination) && $pagination->total_pages > 1): ?>
    <?php

    $pages = $pagination->total_pages;
    $current_page = $pagination->current_page;
    $array_index = $current_page - 1;
    $number_show = 9;

    $pages_array = range(1, $pages);

    if ($pages > $number_show)
    {
        $offset = max(0, $array_index - floor($number_show / 2));
        $offset += min(0, $pages - ($offset + $number_show));
    }
    else
    {
        $offset = 0;
    }

    ?>
    <nav>
        <ul class="pagination">
            <?php if($current_page !== 1): ?>
                <?php $http_query['page'] = ($current_page - 1); ?>
                <li>
                    <a href="<?php echo pagination_url($pagination_url, ($current_page - 1)); ?>"><i class="fa fa-chevron-left"></i></a>
                </li>
            <?php endif; ?>
            <?php foreach(array_slice($pages_array, $offset, $number_show) as $number): ?>
                <li<?php if($number == $current_page) echo ' class="active"'; ?>>
                    <a href="<?php echo pagination_url($pagination_url, $number); ?>"><?php echo $number; ?></a>
                </li>
            <?php endforeach; ?>
            <?php if ($current_page != $pages): ?>
                <li>
                    <a href="<?php echo pagination_url($pagination_url, ($current_page + 1)); ?>"><i class="fa fa-chevron-right"></i></a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
<?php endif ?>
