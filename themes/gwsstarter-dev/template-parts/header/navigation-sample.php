<?php
/**
 * Template part for displaying the header navigation sample menu.
 *
 * @package wp_rig
 */

namespace WP_Rig\WP_Rig;

?>
<nav id="site-navigation" class="main-navigation nav--toggle-sub nav--toggle-small nav:flex-grow" aria-label="Main menu">
	<button class="menu-toggle" aria-label="Open menu" aria-controls="primary-menu" aria-expanded="false">
		Menu
	</button>
	<div class="nav-container">
		<div class="primary-menu-container">
			<ul id="primary-menu" class="menu">
				<li class="menu-item"><a href="#">Home</a></li>
				<li class="menu-item menu-item-has-children menu-item--has-toggle">
					<a href="#">Services</a>
					<button class="dropdown-toggle" aria-expanded="false" aria-label="Expand child menu"><i class="dropdown-symbol"></i></button>
					<ul class="sub-menu">
						<li class="menu-item"><a href="#">Sample Page</a></li>
						<li class="menu-item"><a href="#">Sample Page</a></li>
						<li class="menu-item"><a href="#">Sample Page</a></li>
					</ul>
				</li>
				<li class="menu-item"><a href="#">More Info</a></li>
				<li class="menu-item"><a href="#">About</a></li>
				<li class="menu-item"><a href="#">Contact</a></li>
			</ul>
		</div>
	</div>
</nav>
