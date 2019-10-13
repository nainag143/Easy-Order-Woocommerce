<?php 
 /*Template Name: aco _order*/
get_header();
 ?>

          
         <style>
			 .button,button{
			     width: 20px;
    float: right;
    position: relative;
    background: #889400 !important;
    border: 0px !important;
    padding-left: 0px;
    padding-right: 0px;
    min-width: auto;
    font-size: 16px;
			 }
	 .wc-forward{
    float: right;
    height: 46px;
    padding: 12px;
			 }
			 
			 
			 </style>
 
 
<div class="container" style="width:100%;">
	
	<div class="col-md-8 col-xs-12">
<div class="panel-group" id="accordion">
    
				<?php
				$get_woo_cats = array(
					'taxonomy'     => 'product_cat',
					'orderby'      => 'name',
					'hide_empty'   => '1',
					 'hide_empty' => false, 
                            'parent' => 0,
					
				);
				$woo_categories = get_categories( $get_woo_cats );
 
				foreach ( $woo_categories as $woo_category ) {

				// custom query for woocommerce category
				$args_woo_cat = array(
					'post_type' => 'product',
					'tax_query' => array(
						array(
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $woo_category->term_id,
							'hide_empty' => false, 
							'parent' => 0,
						),
					),
					'posts_per_page' => 100
				);

				$get_featured_cat = new WP_Query( $args_woo_cat );

				if ( $get_featured_cat -> have_posts() ) : ?>
		 
	
							
							
				

    <div class="panel panel-default">
      <div class="panel-heading">
        <h4 class="panel-title">
 			
          <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $woo_category->term_id; ?>">
          	<!--<?php //if(!empty($woo_category->description)) : ?>
							<h3 class="page-sub-title"><?php //echo $woo_category->description; ?>
							</h3>
							<?php //endif; ?> <a href="<?php //echo esc_url( get_category_link( $woo_category->term_id ) ); ?>"><?php //esc_html_e( 'View all', 'estore' ); ?></a>-->
							
							 
			<?php echo $woo_category->name; ?>
							</a>
			 
        </h4>
      </div>
      <div id="collapse<?php echo $woo_category->term_id; ?>" class="panel-collapse collapse">
        <div class="panel-body">
            <!--SHUBHANGI added-->
         <div class="col-md-3" style="padding:0px;">
            <!-- required for floating -->
            <!-- Nav tabs -->
            <ul class="nav nav-tabs tabs-left">
            
            <?php
            $parent_cat_ID=$woo_category->term_id;
            $args = array(
       'hierarchical' => 1,
       'show_option_none' => '',
       'hide_empty' => 0,
       'parent' => $parent_cat_ID,
       'taxonomy' => 'product_cat'
    );
  $subcats = get_categories($args);
     
      foreach ($subcats as $sc) {
        $link = get_term_link( $sc->slug, $sc->taxonomy );
          echo '<li><a href="#'.$sc->slug.'" data-toggle="tab">'.$sc->name.'</a></li>';
      }
      
      
      ?>
             
            </ul>
        </div>
        <div class="col-xs-9">
            <!-- Tab panes -->
            <div class="tab-content">
            <?php
  foreach ($subcats as $sc) {
       // $link = get_term_link( $sc->slug, $sc->taxonomy );
          echo '<div class="tab-pane" id="'.$sc->slug.'">'.$sc->name.'';
      

$args1 = array( 'post_type' => 'product','product_cat' => ''.$sc->slug.'', 'orderby' =>'date','order' => 'ASC' );
 
  $loop1 = new WP_Query( $args1 );
while ( $loop1->have_posts() ) : $loop1->the_post();
global $product; 
?>
 <table class="table">
    <thead>
      <tr>
        <th>NAME</th>
        <th>IMAGE</th>
        <th>PRICE</th>
        <th>QUANTITY</th>
        <th></th>
      </tr>
    </thead>
    <tbody> 
                        <tr>
                            <td style="color:black"><a href="<?php echo get_permalink(); ?>"><?php echo get_the_title()?> 
                                </a></td>
                        <td style="color:black"> <?php echo  woocommerce_get_product_thumbnail()?>
                            </td>
                        <td style="color:black"> <?php echo $product->get_price_html(); ?></td>
                        <td style="color:black"> 

                        <?php
/**
* Loop Add to Cart -- with quantity and AJAX
* requires associated JavaScript file qty-add-to-cart.js
*
* @link http://snippets.webaware.com.au/snippets/woocommerce-add-to-cart-with-quantity-and-ajax/
* @link https://gist.github.com/mikejolley/2793710/
*/
// add this file to folder "woocommerce/loop" inside theme
global $product;
if( $product->get_price() === '' && $product->product_type != 'external' ) return;
// script for add-to-cart with qty
wp_enqueue_script('qty-add-to-cart', get_stylesheet_directory_uri() . '/js/qty-add-to-cart.js', array('jquery'), '1.0.1', true);
?>

<?php if ( ! $product->is_in_stock() ) : ?>

    <a href="<?php echo get_permalink($product->id); ?>" class="button"><?php echo apply_filters('out_of_stock_add_to_cart_text', __('Read More', 'woocommerce')); ?></a>

<?php else : ?>

    <?php
        switch ( $product->product_type ) {
            case "variable" :
                $link   = get_permalink($product->id);
                $label  = apply_filters('variable_add_to_cart_text', __('Select options', 'woocommerce'));
            break;
            case "grouped" :
                $link   = get_permalink($product->id);
                $label  = apply_filters('grouped_add_to_cart_text', __('View options', 'woocommerce'));
            break;
            case "external" :
                $link   = get_permalink($product->id);
                $label  = apply_filters('external_add_to_cart_text', __('Read More', 'woocommerce'));
            break;
            default :
                $link   = esc_url( $product->add_to_cart_url() );
                $label  = apply_filters('add_to_cart_text', __('Add to cart', 'woocommerce'));
            break;
        }
        //printf('<a href="%s" rel="nofollow" data-product_id="%s" class="button add_to_cart_button product_type_%s">%s</a>', $link, $product->id, $product->product_type, $label);
        if ( $product->product_type == 'simple' ) {
            ?>
            <form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="cart" method="post" enctype='multipart/form-data'>

                <?php woocommerce_quantity_input(); ?>

                <button type="submit" data-quantity="1" data-product_id="<?php echo $product->id; ?>"
                    class="button alt ajax_add_to_cart add_to_cart_button product_type_simple"style="
    width: 20px;
    float: right;
    position: relative;
    background: #889400 !important;
    border: 0px !important;
    padding-left: 0px;
    padding-right: 0px;
"><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>

            </form>
            <?php
        } else {
            printf('<a href="%s" rel="nofollow" data-product_id="%s" class="button add_to_cart_button product_type_%s"><i class="fa fa-eye" aria-hidden="true"></i></a>', $link, $product->id, $product->product_type, $label);
        }
    ?>

<?php endif; ?> </td>
                        
                        </tr>
                         
                    </tbody>
                    </table>
    
<?php
endwhile;
wp_reset_query();
      echo '</div>';
      }
      
            ?>
                
                 
            </div>
        </div> 
        	<style>
			td img{
				width:94px
			}
			 
 ul.products li.product {
    clear: none;
    width: 15% !important;
    float: left;
    font-size: .875em;
}
.panel-default > .panel-heading {
    color: #fff;
    background-color: #889400;
    border-color: #ddd;
    text-align: center;
}
			</style>

				    </div>
      </div>
    </div>

				<?php wp_reset_postdata(); ?>

				<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

					<?php wc_get_template( 'loop/no-products-found.php' ); ?>

				<?php endif; ?>

				<?php } // endforeach ?>

</div>
		
		
		</div>
	<div class="col-md-4 col-xs-12" 		style="
    background: #eae4e4;
    padding: 0px;
">	<?php dynamic_sidebar( 'sidebar-1' ); ?>
	
	</div>

</div>

			 </div>
             <div class="tagline">"Where Tasteful Creations Begin.."</div>
        <script>
			/*!
script for WooCommerce add to cart with quantity, via AJAX
Author: support@webaware.com.au
Author URI: http://snippets.webaware.com.au/
License: GPLv2 or later
Version: 1.0.1
*/

// @link http://snippets.webaware.com.au/snippets/woocommerce-add-to-cart-with-quantity-and-ajax/
// @link https://gist.github.com/mikejolley/2793710/

// add this file to folder "js" inside theme

jQuery(function ($) {

    /* when product quantity changes, update quantity attribute on add-to-cart button */
    $("form.cart").on("change", "input.qty", function() {
        if (this.value === "0")
            this.value = "1";

        $(this.form).find("button[data-quantity]").data("quantity", this.value);
    });

    /* remove old "view cart" text, only need latest one thanks! */
    $(document.body).on("adding_to_cart", function() {
        $("a.added_to_cart").remove();
    });

});
			</script>
		<?php get_footer(); ?>
