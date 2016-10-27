<?php
/**
 * Grade Book
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( ! is_admin() ) { exit; }
?>

<div class="wrap lifterlms llms-grade-book-wrap">

	<form action="<?php echo admin_url( 'admin.php' ); ?>" class="llms-grade-book-nav" id="llms-grade-book-filters-form" method="GET">

		<nav class="llms-nav-tab-wrapper">

			<ul class="llms-nav-items">
			<?php foreach ( $tabs as $name => $label ) : ?>

				<?php $current_tab_class = ( $current_tab == $name ) ? ' llms-active' : ''; ?>
				<li class="llms-nav-item<?php echo $current_tab_class; ?>"><a class="llms-nav-link" href="<?php echo admin_url( 'admin.php?page=llms-grade-book&tab=' . $name ); ?>"><?php echo $label; ?></a>

			<?php endforeach; ?>
			</ul>

		</nav>

	</form>

	<h1 style="display:none;"></h1><!-- find a home for admin notices -->

	<div class="llms-options-page-contents">

		<?php do_action( 'llms_grade_book_before_content' ); ?>

		<?php do_action( 'llms_grade_book_content' ); ?>

		<?php do_action( 'llms_grade_book_after_content' ); ?>

	</div>

</div>
