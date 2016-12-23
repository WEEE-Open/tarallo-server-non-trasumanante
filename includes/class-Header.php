<?php
# Copyright (C) 2016 T.A.R.A.L.L.O. - HTML Header
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

class Header {
	function __construct($menu_uid = null, $args = [] ) {
		header("Content-Type: text/html; charset=" . CHARSET);

		$menu = get_menu_entry($menu_uid);

		$args = merge_args_defaults($args, [
			'title' => $menu->name,
			'url'   => $menu->url,
			'og'    => []
		] );

		$args['og'] = merge_args_defaults($args['og'], [
			'title' => $args['title'],
                	'type'  => 'website',
			'url'   => $args['url']
		] );

		//$l = latest_language();
		//if($l) {
		//	$l = $l->getISO();
		//} else {
			$l = 'it';
		//}
?>
<!DOCTYPE html>
<html lang="<?php echo $l ?>">
<head>
	<title><?php echo $args['title'] ?></title>
	<?php load_module('header') ?>

	<?php foreach($args['og'] as $og=>$value): ?>
		<meta property="og:<?php echo $og ?>" content="<?php _esc_attr( $value ) ?>" />
	<?php endforeach ?>

</head>
<body>
	<header>
		<div class="center">
			<h1><?php echo $args['title'] ?></h1>
		</div>
		<div class="container">
			<?php if( isset( $args['url'] ) ): ?>
				<h2><a href="<?php echo $args['url'] ?>"><?php echo $args['title'] ?></a></h2>
			<?php else: ?>
				<h2><?php echo $args['title'] ?></h2>
			<?php endif ?>

		</div>
		<!-- End container -->
	</header>
	<div class="container">

	<?php
	}
}
