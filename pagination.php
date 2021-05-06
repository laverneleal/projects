<nav>
	<ul class="pagination">
	<?php
		//Abstraction
		//loop
			for( $i = 1; $i <= ceil( $total_cnt / $perpage ); $i++ ) {
				if( $i == $page ) {
					echo '<li class="active"><span class="page">'.$i.'</span></li>';
				}else{
					echo '<li><span class="page">'.$i.'</span></li>';
				}
			}
	?>
	</ul>
</nav>


