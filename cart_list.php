<header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-center mb-4 page-title">
                    	<h1 class="text-white">Shopping Cart</h1>
                        <hr class="divider my-4 bg-dark" />
                    </div>
                    
                </div>
            </div>
        </header>
	<section class="page-section" id="menu">
        <div class="container">
        	<div class="row">
        	<div class="col-lg-8">
        		<div class="sticky">
	        		<div class="card">
	        			<div class="card-body">
	        				<div class="row">
		        				<div class="col-md-8"><b>Items</b></div>
		        				<div class="col-md-4 text-right"><b>Total</b></div>
	        				</div>
	        			</div>
	        		</div>
	        	</div>
        		<?php 
				
				$data = "";

					if (isset($_SESSION['login_user_id'])) {
						$data = "where c.user_id = '" . $_SESSION['login_user_id'] . "' ";	
					} else {
						$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
						$data = "where c.client_ip = '".$ip."' ";	
					}

					$get = $conn->query("SELECT *,c.id as cid FROM cart c inner join product_list p on p.id = c.product_id ".$data);

					// Check if $get is not null before using it
					if ($get !== null) {
						$num_items = $get->num_rows;
						$shipping = 0;
						// ... rest of your code
					}
				// Determine the shipping fee based on the number of items in the cart
					$num_items = $get->num_rows;
					if ($num_items == 1) {
						$shipping = 100;
					} elseif ($num_items >= 2 && $num_items <= 3) {
						$shipping = 50;
					} elseif ($num_items >= 4) {
						$shipping = 40;
					}
					$total = 0;

					while ($row = $get->fetch_assoc()) {
						$total += ($row['qty'] * $row['price']);
					}

					// Calculate the final total amount including shipping
					$total_amount = $total + $shipping;
					//  else {
					// // Handle the case when $get is null (e.g., database connection issue)
					// echo "Error fetching cart items.";
					// }
				// 	// // Add these lines for debugging
				// 	// echo "Number of items: " . $num_items . "<br>";
				// 	// echo "Shipping fee: " . $shipping . "<br>";

        		if(isset($_SESSION['login_user_id'])){
					$data = "where c.user_id = '".$_SESSION['login_user_id']."' ";	
				}else{
					$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
					$data = "where c.client_ip = '".$ip."' ";	
				}
				$total = 0;
				$get = $conn->query("SELECT *,c.id as cid FROM cart c inner join product_list p on p.id = c.product_id ".$data);
				while($row= $get->fetch_assoc()):
					$total += ($row['qty'] * $row['price']);
        		?>

        		<div class="card">
	        		<div class="card-body">
		        		<div class="row">
			        		<div class="col-md-4 d-flex align-items-center" style="text-align: -webkit-center">
								<div class="col-auto">	
			        				<a href="admin/ajax.php?action=delete_cart&id=<?php echo $row['cid'] ?>" class="rem_cart btn btn-sm btn-outline-danger" data-id="<?php echo $row['cid'] ?>"><i class="fa fa-trash"></i></a>
								</div>	
								<div class="col-auto flex-shrink-1 flex-grow-1 text-center">	
			        				<img src="assets/img/<?php echo $row['img_path'] ?>" alt="">
								</div>	
			        		</div>
			        		<div class="col-md-4">
			        			<p><b><large><?php echo $row['name'] ?></large></b></p>
			        			<p class='truncate'> <b><small>Desc :<?php echo $row['description'] ?></small></b></p>
			        			<p> <b><small>Unit Price :<?php echo number_format($row['price'],2) ?></small></b></p>
			        			<p><small>QTY :</small></p>
			        			<div class="input-group mb-3">
								  <div class="input-group-prepend">
								    <button class="btn btn-outline-secondary qty-minus" type="button"   data-id="<?php echo $row['cid'] ?>"><span class="fa fa-minus"></button>
								  </div>
								  <input type="number" readonly value="<?php echo $row['qty'] ?>" min = 1 class="form-control text-center" name="qty" >
								  <div class="input-group-prepend">
								    <button class="btn btn-outline-secondary qty-plus" type="button" id=""  data-id="<?php echo $row['cid'] ?>"><span class="fa fa-plus"></span></button>
								  </div>
								</div>
			        		</div>
			        		<div class="col-md-4 text-right">
			        			<b><large><?php echo number_format($row['qty'] * $row['price'],2) ?></large></b>
			        		</div>
		        		</div>
	        		</div>
	        	</div>

	        <?php endwhile; ?>
        	</div>
        	<div class="col-md-4">
        		<div class="sticky">
        			<div class="card">
        				<div class="card-body">
        					<p><large>Amount</large></p>
        					<hr>
        					<p class="text-right"><b><?php echo number_format($total,2) ?></b></p>
        					<hr>
							<div class="text-center">
                    			<p><b><small>Shipping Fee: <?php echo number_format($shipping, 2); ?></small></b></p>
								<p><b><small>Total Amount: <?php echo number_format($total_amount, 2); ?></small></b></p>
                			</div>
        					<div class="text-center">
        						<button class="btn btn-block btn-outline-dark" type="button" id="checkout">Proceed to Checkout</button>
        					</div>
        				</div>
        			</div>
        		</div>
        	</div>
        	</div>
        </div>
    </section>
    <style>
    	.card p {
    		margin: unset
    	}
    	.card img{
		    max-width: calc(100%);
		    max-height: calc(59%);
    	}
    	div.sticky {
		  position: -webkit-sticky; /* Safari */
		  position: sticky;
		  top: 4.7em;
		  z-index: 10;
		  background: white
		}
		.rem_cart{
		   position: absolute;
    	   left: 0;
		}
    </style>
    <script>
       $(document).ready(function(){
				$('.qty-minus').click(function(){
					var qtyInput = $(this).parent().siblings('input[name="qty"]');
					var qty = parseInt(qtyInput.val());
					if(qty > 1){
						qtyInput.val(qty - 1);
						update_qty(qty - 1, $(this).attr('data-id'));
					}
				});

				$('.qty-plus').click(function(){
					var qtyInput = $(this).parent().siblings('input[name="qty"]');
					var qty = parseInt(qtyInput.val());
					qtyInput.val(qty + 1);
					update_qty(qty + 1, $(this).attr('data-id'));
				});

				function update_qty(qty, id){
					start_load();
					$.ajax({
						url:'admin/ajax.php?action=update_cart_qty',
						method:"POST",
						data:{id:id, qty: qty},
						success:function(resp){
							if(resp == 1){
								load_cart();
								end_load();
							}
						}
					});
				}
				});

        $('.view_prod').click(function(){
            uni_modal_right('Product','view_prod.php?id='+$(this).attr('data-id'))
        })
        $('.qty-minus').click(function(){
		var qty = $(this).parent().siblings('input[name="qty"]').val();
		update_qty(parseInt(qty) -1,$(this).attr('data-id'))
		if(qty == 1){
			return false;
		}else{
			 $(this).parent().siblings('input[name="qty"]').val(parseInt(qty) -1);
		}
		})
		$('.qty-plus').click(function(){
			var qty =  $(this).parent().siblings('input[name="qty"]').val();
				 $(this).parent().siblings('input[name="qty"]').val(parseInt(qty) +1);
		update_qty(parseInt(qty) +1,$(this).attr('data-id'))
		})
		function update_qty(qty,id){
			start_load()
			$.ajax({
				url:'admin/ajax.php?action=update_cart_qty',
				method:"POST",
				data:{id:id,qty},
				success:function(resp){
					if(resp == 1){
						load_cart()
						end_load()
					}
				}
			})

		}
		$('#checkout').click(function(){
			if('<?php echo isset($_SESSION['login_user_id']) ?>' == 1){
				location.replace("index.php?page=checkout")
			}else{
				uni_modal("Checkout","login.php?page=checkout")
			}
		})
			$('.qty-minus').click(function(){
				// Handle quantity decrement
				// Update the quantity and call the update_qty function
			});

			$('.qty-plus').click(function(){
				// Handle quantity increment
				// Update the quantity and call the update_qty function
			});

			function update_qty(qty, id){
				// Send an AJAX request to update the quantity in the database
				// Reload the cart after successful update
			}

			$('#checkout').click(function(){
				// Handle the checkout process
				// Redirect to the checkout page if the user is logged in; otherwise, show a login page
			});
    </script>