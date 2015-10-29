<?php
/**
 * Created by PhpStorm.
 * User: witoldklimczak
 * Date: 7/7/15
 * Time: 12:39 PM
 */

$args = array(
    'type'                     => 'venture',
    'taxonomy'                 => 'venture_categories',
);
$categories = get_categories( $args );
$parents = array();
$childrens = array();

foreach ($categories as $category) {
    if ($category->parent == 0)
        $parents[] = $category;
    else
        if (isset($childrens[$category->parent]))
            $childrens[$category->parent] .= '###' . $category->slug . '#' . $category->name;
        else
            $childrens[$category->parent] = $category->slug . '#' . $category->name;
}

?>

<script>
    function getState(val) {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url() . '/wp-content/themes/mustard-seed/partials/get_category.php'; ?>",
            data:'category='+val,
            success: function(data){
                $("select[id='subcategories']")
                    .html('<option></option>' + data)
                    .trigger("chosen:updated");
            }
        });
    }
</script>

<div id="container" class="container-fluid">
    <div class="inner-container">
        <div class="full-text">

            <form style="margin:auto" role="search" method="get" id="searchform" class="search-form" action="<?php echo get_home_url(); ?>">
                <div>
                    <input type="text" value="" name="s" id="s" placeholder="Search keyword, company name etc." />
                    <button type="submit" id="searchsubmit">Search</button>

                    <div class="search-form-cols">
                        <div class="search-form-col">
                            <select class="custom-select" onChange="getState(this.value);" data-placeholder="Filter by category">
                                <option></option>
                                <?php
                                foreach ($parents as $category) {
                                    echo '<option value="' . $childrens[$category->term_id] . '">' . $category->name . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <div class="search-form-col">
                            <select class="custom-select" name="venture_categories" id="subcategories" data-placeholder="Subcategory">
                            </select>
                        </div>

                        <div class="search-form-col">
                            <select class="custom-select" name="order_by" id="order_by" data-placeholder="Order by">
                                <option value="relevant">Most Relevant</option>
                                <option value="recent">Most Recent</option>
                                <option value="viewed">Most Viewed</option>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" value="venture" name="post_type" />
                </div>
            </form>

        </div>
    </div>
</div>