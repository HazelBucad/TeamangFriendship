<div class="container-fluid">
    <?php
    $total = 0;
    $shipping = 0; // Initialize shipping with a default value

    include 'db_connect.php';

    $qry = $conn->query("SELECT * FROM order_list o inner join product_list p on o.product_id = p.id  where order_id =" . $_GET['id']);
    $num_items = $qry->num_rows; // Get the number of items in the order

    // Determine the shipping fee based on the number of items in the order
    if ($num_items == 1) {
        $shipping = 100;
    } elseif ($num_items >= 2 && $num_items <= 3) {
        $shipping = 50;
    } elseif ($num_items >= 4) {
        $shipping = 40;
    }

    // Fetch all order details and store them in an array
    $orderDetails = [];
    while ($row = $qry->fetch_assoc()) {
        $orderDetails[] = $row;
    }
    ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Qty</th>
                <th>Order</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orderDetails as $row): ?>
                <tr>
                    <td><?php echo $row['qty'] ?></td>
                    <td><?php echo $row['name'] ?></td>
                    <td><?php echo number_format($row['qty'] * $row['price'], 2) ?></td>
                </tr>
                <?php $total += $row['qty'] * $row['price']; ?>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Shipping Fee:</th>
                <th><?php echo number_format($shipping, 2) ?></th>
            </tr>
            <tr>
                <th colspan="2" class="text-right">TOTAL</th>
                <th><?php echo number_format($total + $shipping, 2) ?></th>
            </tr>
        </tfoot>
    </table>
    <div class="text-center">
        <button class="btn btn-primary" id="confirm" type="button" onclick="confirm_order()">Confirm</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>

<style>
    #uni_modal .modal-footer {
        display: none
    }
</style>

<script>
    function confirm_order() {
        start_load()
        $.ajax({
            url: 'ajax.php?action=confirm_order',
            method: 'POST',
            data: {
                id: '<?php echo $_GET['id'] ?>'
            },
            success: function (resp) {
                if (resp == 1) {
                    alert_toast("Order confirmed.")
                    setTimeout(function () {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>
