<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Emigo: Powered by Emigo">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="Grocery, Store, stores">
    <title>Emigo</title>
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo site_url() ?>assets/website/images/fav.webp">
    <!-- plugins css -->
    <link rel="stylesheet preload" href="<?php echo site_url() ?>assets/website/css/plugins.css" as="style">
    <link rel="stylesheet preload" href="<?php echo site_url() ?>assets/website/css/style.css" as="style">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo site_url() ?>assets/admin/css/user-custom-styles.css" as="style">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <style>
    body {
        font-family: 'Poppins' !important;
    }
    </style>
</head>

<body class="shop-main-h">
    <input type="hidden" id="store_phone" value="<?php echo $store_phone; ?>">
    <input type="hidden" id="store_id" value="<?php echo $store_id; ?>">
    <input type="hidden" id="language" name="language" value="<?php echo $language; ?>">
    <div class="weekly-best-selling-area bg_light-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cover-card-main-over1">

                        <div class="container" style="padding: 0px !important;">

                            <ul class="nav-h_top language p-0">
                                <li class="category-hover-header language-hover">
                                    <a href="#">
                                        <?php if(isset($language)){
                                                 if($language == 'ma'){
                                                    $lang = 'മലയാളം';
                                                }
                                                if($language == 'en'){
                                                    $lang = 'English';
                                                }
                                                if($language == 'hi'){
                                                    $lang = 'हिन्दी';
                                                }
                                                if($language == 'ar'){
                                                    $lang = 'وتُكتب';
                                                }
                                                echo $lang;
                                            } ?>
                                    </a>
                                    <ul class="category-sub-menu">
                                        <?php $selected_languages = explode(",", $store_selected_languages );
                                        foreach ($selected_languages as $languag){ ?>
                                        <li class="category-hover-header language-hover">
                                            <?php if($languag == 'ma'){
                                                $lang = 'മലയാളം';
                                            }
                                            if($languag == 'en'){
                                                $lang = 'English';
                                            }
                                            if($languag == 'hi'){
                                                $lang = 'हिन्दी';
                                            }
                                            if($languag == 'ar'){
                                                $lang = 'وتُكتب';
                                            }
                                            ?>
                                            <a href="<?php echo base_url('website/products/set_language/'.$languag); ?>"
                                                class="menu-item">
                                                <span><?php echo $lang; ?></span>
                                            </a>
                                        </li>
                                        <?php } ?>

                                    </ul>
                                </li>
                            </ul>
                            <div class="row">
                                <div class="col-lg-12 text-center">
                                    <img class="logo"
                                        src="<?php if(isset($store_informations->store_logo_image)){ echo base_url()."uploads/store/".$store_informations->store_logo_image;} ?>"
                                        alt="Veg">
                                    <h4 style="color: #914747;font-size: 18px;" class="title-left text-uppercase mb-0">
                                        <?php echo $store_informations->store_name; ?> </h4>
                                </div>
                                <div class="col-lg-12">
                                    <div class="title_desc">
                                        <p><?php echo $store_informations->store_desc; ?></p>
                                    </div>
                                    <hr class="separation-b">
                                    <div class="title-area-between mb-0">

                                        <ul class="nav nav-tabs best-selling-grocery food-type" id="myTab"
                                            role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button onclick="loadCategories('veg')" class="nav-link active"
                                                    id="veg-tab" data-bs-toggle="tab" data-bs-target="#veg"
                                                    type="button" role="tab" aria-controls="veg"
                                                    aria-selected="true"><img class="veg"
                                                        src="<?php echo base_url(); ?>assets/website/images/veg.png">
                                                    Veg</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button onclick="loadCategories('non-veg')" class="nav-link"
                                                    id="non-veg-tab" data-bs-toggle="tab" data-bs-target="#non-veg"
                                                    type="button" role="tab" aria-controls="non-veg"
                                                    aria-selected="false"><img class="veg"
                                                        src="<?php echo base_url(); ?>assets/website/images/nonveg.png">
                                                    Non-veg</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <div class="dropdown">
                                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-three-dots"></i> Filters
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                                        <?php foreach($categories as $category){ ?>
                                                        <li>
                                                            <a onclick="loadProductsCategoryFilter(<?php echo $category['category_id']; ?>)"
                                                                class="dropdown-item category" href="#">
                                                                <i class="bi bi-eye"></i>
                                                                <?php echo $category['category_name_'.$language]; ?>
                                                            </a>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <div class="dropdown d-none">
                                                    <button class="btn btn-primary dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        <i class="bi bi-three-dots"></i> Sub-Filter
                                                    </button>
                                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <li>
                                                            <a onclick="loadProductsCategoryFilter('all')"
                                                                class="dropdown-item category" href="#">
                                                                <i class="bi bi-eye"></i> All
                                                            </a>
                                                        </li>
                                                        <?php foreach($subcategories as $category){ ?>
                                                        <li>
                                                            <a onclick="loadProductsSubCategoryFilter(<?php echo $category['subcategory_id']; ?>)"
                                                                class="dropdown-item category" href="#">
                                                                <i class="bi bi-eye"></i>
                                                                <?php echo $category['subcategory_name_'.$language]; ?>
                                                            </a>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active clear" style="padding:5px;"
                                                    type="button">Clear</button>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>

                            <hr>


                            <!-- <div class="accordion" id="products-content">

                            </div> -->

                            <div class="user-product-list">
                                <div class="user-product-list__content" id="products-content">
                                    <!-- Ajax response here -->
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <hr class="separation-b">
            </div>

        </div>
    </div>
    </div>
    </div>
    </div>
    </div>



    <div class="fixed-cart-total"
        style="text-align:left;position:fixed;bottom:0px;width:100%;background: #ef4f5f;color: #fff;text-align: center;padding: 10px 20px;font-size: 15px; display: none; align-items: center; justify-content: space-between; gap: 10px;">
        <a href="<?php echo site_url() ?>cart/view" style="color: #fff;text-decoration: none;">
            <p class="cart-total" style="color: #fff;font-size: 14px;font-weight: bold; margin: 0;margin-top: 2px;">
                <span id="total-items">YOUR ORDER <br><span id="cart-total"></span></span>
            </p>
        </a>
        <a href="<?php echo site_url() ?>cart/view" style="background: #ffffff;
    color: #000000;
    padding: 6px 14px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 16px;">View cart <i class="fa fa-arrow-right arrow-next"></i></a>
    </div>

    <!--<div class="fixed-cart-total" style="position:fixed;bottom:0px;width:100%;background: #ef4f5f;color: #fff;text-align: center;padding: 13px;font-size: 15px;border-radius: 14px 14px 0px 2px;">-->
    <!--<a href="<?php echo site_url() ?>cart/view"><p class="cart-total" style="color: #fff;font-size: 16px;font-size: 16px;font-weight: bold;"><span id="total-items"> PLACE ORDER <span id="cart-total"></span>  </p></a>-->


    </div>
    <!-- rts copyright-area start -->
    <div class="rts-copyright-area d-none">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="copyright-between-1">
                        <p class="disc">
                            Copyright 2024 <a href="#">EMIGO</a>. All rights reserved.
                        </p>
                        <!-- <a href="#" class="playstore-app-area">
                            <span>Download App</span>
                            <img src="<?php echo site_url() ?>assets/website/images/googleplay.webp" alt="">
                            <img src="<?php echo site_url() ?>assets/website/images/appstore.webp" alt="">
                        </a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- rts copyright-area end -->





    <!-- Modal structure -->
    <div class="modal fade" id="productDetails" tabindex="-1" aria-labelledby="productCustomizeLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-capitalize" id="ProductName"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="DetailsProdContent">
                    <img class="border8" id="prodImage">
                    <p id="productDesc" class="mb-2 mt-2"></p>
                    <h5 id="prodRate" class="text-end mb-0"></h5>
                </div>
            </div>
        </div>
    </div>


    <!-- Validation -->
    <!-- The Modal -->
    <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    This is a simple Bootstrap modal popup.
                </div>
            </div>
        </div>
    </div>
    <!-- validation -->






    <!-- customize modal -->
    <div class="modal fade" id="customizeModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- <iframe id="iframe_product_customize" height="700px" width="100%"></iframe> -->

            </div>
        </div>
    </div>
    <!--customize modal end -->





    <div class="modal fade" id="productCustomize" tabindex="-1" role="dialog" aria-labelledby="foodModalLabel"
        data-bs-backdrop="static" aria-hidden="true" data-bs-keyboard="false">
        <div class="modal-dialog customize-modal" role="document">
            <div class="modal-content">

                <div class="modal-body" id="modalBodyContent">
                    <button type="button" class="btn-close emigo-close-btn" data-bs-dismiss="modal"
                        aria-label="Close"></button>


                    <button type="button" class="btn btn-block btn-popup"></button>


                </div>



            </div>
        </div>

    </div>
    </div>
    </div>


    <div class="cart d-none">
        <h2>Cart</h2>
        <p>Total Items: <span id="total-items"></span></p>
        <ul id="cart-items"></ul>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <!-- plugins js -->
    <script defer src="<?php echo site_url() ?>assets/website/js/plugins.js"></script>

    <!-- custom js -->
    <script defer src="<?php echo site_url() ?>assets/website/js/main.js"></script>
    <!-- header style two End -->


</body>

</html>

<script>
function loadProductsCategoryFilter(category) {
    //alert('category filter');
    $.ajax({
        url: '<?= base_url("website/products/loadProductsCategoryFilter") ?>',
        type: 'POST',
        data: {
            category: category,
            store_id: $('#store_id').val(),
            language: $('#language').val()
        },
        dataType: 'text', // Expect JSON response
        success: function(response) {
            //alert(response); // Inspect the data to ensure it's being received correctly
            $('#products-content').html(response);
            initializeProducts();
            // Initialize accordion functionality if needed
        },
        error: function() {
            alert("An error occurred while loading categories.");
        }
    });
}


function loadProductsSubCategoryFilter(subcategory) {
    const hidden_category_id = $('#hidden_category_id').val(); //alert(hidden_category_id);
    $.ajax({
        url: '<?= base_url("website/products/loadProductsSubCategoryFilter") ?>',
        type: 'POST',
        data: {
            category: hidden_category_id,
            subcategory: subcategory,
            store_id: $('#store_id').val(),
            language: $('#language').val()
        },
        dataType: 'text', // Expect JSON response
        success: function(response) {
            //alert(response); // Inspect the data to ensure it's being received correctly
            $('#products-content').html(response);
            initializeProducts();
            // Initialize accordion functionality if needed
        },
        error: function() {
            alert("An error occurred while loading categories.");
        }
    });
}
</script>




<script>
function loadCategories(type) {
    const hidden_category_id = $('#hidden_category_id').val(); //alert(hidden_category_id);
    $.ajax({
        url: '<?= base_url("website/products/loadProductsTypeFilter") ?>',
        type: 'POST',
        data: {
            type: type,
            store_id: $('#store_id').val(),
            language: $('#language').val(),
            hidden_category_id: hidden_category_id
        },
        dataType: 'text', // Expect JSON response
        success: function(response) {
            //alert(response); // Inspect the data to ensure it's being received correctly
            $('#products-content').html(response);
            initializeProducts();
            // Initialize accordion functionality if needed
        },
        error: function() {
            alert("An error occurred while loading categories.");
        }
    });
}
</script>

<script>
function copyToClipboard(element) {
    var text = element.textContent;
    $('#output-textarea').val(text);
}
</script>


<script>
function initializeProducts() {
    $('.product').each(function() {
        $('.product').each(function() {
            //alert('rr');
            var $product = $(this);
            var quantity = parseInt($product.attr('data-quantity')) || 0;

            if (quantity > 0) {
                // If product is in the cart, show quantity controls and set the quantity
                $product.find('.quantityControls').show();
                $product.find('.prod_qty').val(quantity);
                $product.find('.add-to-cart').hide();
            } else {
                // If product is not in the cart, show the "ADD" button
                $product.find('.add-to-cart').show();
                $product.find('.quantityControls').hide();
            }
        });
    });
}
</script>


<script>
$(document).ready(function() {
    loadCart();
    loadProducts();



    // Add to cart
    $(document).on('click', '.add-to-cart', function() {
        $(this).hide(); // Hide the ADD button
        $(this).next(".quantityControls").show(); // Show the quantity controls
        const productId = $(this).closest('.product').data('id');
        const price = $(this).closest('.product').data('price');
        const quantity = 1;
        //alert(productId);

        $.ajax({
            url: '<?= base_url("cart/add") ?>',
            method: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                price: price,
                addon: 0
            },
            success: function() {
                //loadProducts();
                loadCart();
            }
        });
    });


    $(document).on("click", ".clear", function() {
        loadProducts();
    });



    $(document).on("click", ".increment3", function() {
        const product = $(this).closest('.product');
        const productId = product.data('id');
        const quantityInput = product.find('.prod_qty');
        let quantity = parseInt(quantityInput.val()) + 1;
        quantityInput.val(quantity);
        updateCartQuantity(productId, quantity, 0);
    });

    // Decrement quantity
    $(document).on("click", ".decrement3", function() {
        let $qtyInput = $(this).siblings(".prod_qty");
        let currentQty = parseInt($qtyInput.val());
        const product = $(this).closest('.product');
        const productId = product.data('id');
        if (currentQty > 1) {
            const quantityInput = product.find('.prod_qty');
            let quantity = parseInt(quantityInput.val()) - 1;
            quantityInput.val(quantity);
            updateCartQuantity(productId, quantity, 0);
        } else {
            deleteCartItem(productId, 0);
            $(this).parent(".quantityControls").hide(); // Hide quantity controls
            $(this).parent().prev(".add-to-cart").show(); // Show the ADD button
        }
    });



    // Update cart quantity
    function updateCartQuantity(productId, quantity, is_addon) {
        var store_id = $('#store_id').val();
        $.ajax({
            url: '<?= base_url("cart/updateQuantity") ?>',
            method: 'POST',
            data: {
                product_id: productId,
                store_id: store_id,
                quantity: quantity,
                is_addon: is_addon
            },
            success: function(response) {
                if (response != 'success') {
                    $('#validationModal').modal('show');
                    $('#validationModal .modal-body').html(response);
                }
                loadCart();
            }
        });
    }

    // Load cart items and total
    function loadCart() {
        $.ajax({
            url: '<?= base_url("cart/get") ?>',
            method: 'GET',
            success: function(data) {
                const cartData = JSON.parse(data);
                displayCart(cartData);
            }
        });
    }

    function loadProducts() {
        $.ajax({
            url: '<?= base_url("website/products/loadProducts") ?>',
            method: 'GET',
            success: function(data) {
                $('#products-content').html(data);
                initializeProducts();
            }
        });
    }

    // Display cart items and update total items count and total value
    function displayCart(cartData) {
        $('#cart-items').empty();
        let totalItems = 0;
        let cartTotal = 0;

        $.each(cartData, function(id, item) {
            const itemTotal = item.quantity * item.price;
            cartTotal += itemTotal;
            totalItems = parseInt(totalItems) + parseInt(item.quantity);

            $('#cart-items').append(
                `<li>${item.name} - ₹${item.price} x ${item.quantity} = ₹${itemTotal.toFixed(2)}
                <span class="delete-item" data-id="${id}">X</span></li>`
            );
        });

        let total = totalItems;

        function sumOfDigits(number) {
            let sum = 0;
            let digits = number.toString().split(''); // Convert number to a string and split into digits

            digits.forEach(function(digit) {
                sum += parseInt(digit, 10); // Convert each digit back to an integer and add to sum
            });

            return sum;
        }
        if (cartTotal != 0) {
            $('.fixed-cart-total').css('display', 'flex');
            $('#total-items').html(' YOUR ORDER <br><span id="total-cost">Total Cost : ₹' + cartTotal.toFixed(
                2) + '</span>');
        } else {
            $('.fixed-cart-total').css('display', 'none');

        }

        $('#cart-total').text(cartTotal.toFixed(2));
    }

    // Delete item from cart
    $(document).on('click', '.delete-item', function() {
        const productId = $(this).data('id');
        deleteCartItem(productId);
    });

    // Function to delete cart item
    function deleteCartItem(productId, isaddon) {
        $.ajax({
            url: '<?= base_url("cart/delete") ?>',
            method: 'POST',
            data: {
                product_id: productId,
                is_addon: isaddon
            },
            success: function() {
                loadCart();
            }
        });
    }


    // Add customisable product into cart and load cart function
    $(document).on('click', '.add-to-cart-popup', function() {
        const productId = $(this).data('id');
        const recipe = $('#output-textarea').val(); //alert(productId);//alert(recipe);
        let variantIds = [];
        // delete all cart datas using product_id
        $.ajax({
            url: '<?= base_url("cart/deleteparent") ?>',
            method: 'POST',
            data: {
                product_id: productId
            },
            success: function(data) {
                //alert(data)
                $('.variant').each(function() {
                    const price = parseFloat($(this).data('price')); //alert(price);
                    const variantId = parseFloat($(this).data('varid'));
                    const quantity = parseInt($(this).find('.variant-qty').val()) ||
                        0;
                    if (quantity > 0) {
                        //alert(1);
                        variantIds.push({
                            id: variantId,
                            qty: quantity,
                            price: price
                        });
                        console.log(variantIds);
                        $.ajax({
                            url: '<?= base_url("cart/addvariant") ?>',
                            method: 'POST',
                            data: {
                                prdParentId: productId,
                                product_id: productId,
                                quantity: quantity,
                                name: 'name',
                                image: 'image',
                                addon: 0,
                                recipe: recipe,
                                variant_id: variantId,
                                price: price,
                                variantIds: variantIds
                            },
                            success: function(data) {


                                let addonIds = [];
                                $('.addon').each(function() {
                                    //alert('addon');
                                    const price = parseFloat($(
                                        this).data(
                                        'price'
                                    )); //alert(price);
                                    const itemId = parseFloat($(
                                        this).data(
                                        'id'));
                                    const quantity1 = parseInt(
                                        $(this).find(
                                            '.addon-qty')
                                        .val()) || 0;
                                    if (quantity1 > 0) {
                                        //alert(1222);
                                        addonIds.push({
                                            id: itemId,
                                            qty: quantity1,
                                            price: price
                                        });
                                    }





                                    $.ajax({
                                        url: '<?= base_url("cart/addaddon") ?>',
                                        method: 'POST',
                                        data: {
                                            prdParentId: productId,
                                            product_id: productId,
                                            quantity: quantity,
                                            name: 'name',
                                            image: 'image',
                                            addon: 1,
                                            recipe: 'recipe',
                                            variant_id: itemId,
                                            price: price,
                                            addonIds: addonIds
                                        },
                                        success: function(
                                            count
                                        ) { //data return get total count variants and addons(Add through customize window)
                                            $('#productCustomize')
                                                .modal(
                                                    'hide'
                                                );
                                            //alert(productId);
                                            loadCart
                                                ();
                                            if (count >
                                                0) {
                                                $('#quantity_show_' +
                                                        productId
                                                    )
                                                    .removeClass(
                                                        'quantity_hide'
                                                    )
                                                    .addClass(
                                                        'quantity_visible'
                                                    );
                                                $('#quantity_show_' +
                                                        productId
                                                    )
                                                    .text(
                                                        count
                                                    );
                                            } else {
                                                $('#quantity_show_42')
                                                    .addClass(
                                                        'quantity_show'
                                                    );
                                            }
                                        }
                                    });
                                });


                            }
                        });
                    }
                });


                //In case of no addons and no variants (Addons and variants are null)
                $('#productCustomize').modal('hide');
                loadCart();
                $('#quantity_show_' + productId).removeClass('quantity_visible').addClass(
                    'quantity_hide');
                $('#quantity_show_' + productId).text('ADD');



            }
        });
    });
    // end
});
</script>

<script>
$(document).ready(function() {

    function initializeButtonVisibility() {
        $('.add-btn').each(function() {
            const controlGroup = $(this).siblings('.quantity-group');
            // If there's any initial data (like cart state), adjust visibility
            if (controlGroup.find('.variant-qty').val() > 0) {
                $(this).addClass('d-none');
                controlGroup.removeClass('d-none');
            } else {
                $(this).removeClass('d-none');
                controlGroup.addClass('d-none');
            }
        });

        $('.add-btn1').each(function() {
            const controlGroup1 = $(this).siblings('.quantity-group1');
            //alert(controlGroup1.find('.addon-qty').val());

            // If there's any initial data (like cart state), adjust visibility
            if (controlGroup1.find('.addon-qty').val() > 0) {
                $(this).addClass('d-none');
                controlGroup1.removeClass('d-none');
            } else {
                $(this).removeClass('d-none');
                controlGroup1.addClass('d-none');
            }
        });

        $('.add-btn2').each(function() {
            const controlGroup2 = $(this).siblings('.quantity-group2');
            //alert(controlGroup1.find('.addon-qty').val());

            // If there's any initial data (like cart state), adjust visibility
            if (controlGroup2.find('.main-product-qty').val() > 0) {
                $(this).addClass('d-none');
                controlGroup2.removeClass('d-none');
            } else {
                $(this).removeClass('d-none');
                controlGroup2.addClass('d-none');
            }
        });
    }

    // Triggered when the modal is about to be shown
    $('#productCustomize').on('show.bs.modal', function(event) {
        // Get the button that triggered the modal and extract the product ID
        var button = $(event.relatedTarget);
        var productId = button.data('id');
        var quantity = button.data('quantity');
        var product = button.data('prodid'); //alert(product);

        // Send an AJAX request to fetch variants and addons
        $.ajax({
            url: "<?php echo base_url('website/products/getVariantsAndAddons'); ?>",
            type: "POST",
            data: {
                product_id: productId,
                store_id: $('#store_id').val(),
                language: $('#language').val(),
                prod: product,
                quantity: quantity
            },
            success: function(response) {
                //alert(response);
                // Populate the modal with the response data
                $('#modalBodyContent').html(response);
                initializeButtonVisibility();
            },
            error: function() {
                $('#modalBodyContent').html(
                    '<p class="text-danger">Unable to load data. Please try again.</p>');
            }
        });
    });




    // Triggered when the modal is about to be shown
    $('#productDetails').on('show.bs.modal', function(event) {
        // Get the button that triggered the modal and extract the product ID
        var button = $(event.relatedTarget);
        var productId = button.data('id');
        var quantity = button.data('quantity');
        var product = button.data('prodid');
        var name = button.data('name');
        var desc = button.data('desc');
        var rate = button.data('rate');
        var imageUrl = button.data('image');
        $('#ProductName').html(name);
        $('#productDesc').html(desc);
        $('#prodRate').html('₹' + rate);
        $('#prodImage').attr('src', imageUrl);

    });



});
</script>

<script>
// $(document).ready(function() {
//     $(document).on('click', '.seeMoreBtn', function() {
//         const moreText = $(this).siblings(".product-description").find(".more-text");
//         if (moreText.is(":visible")) {
//             moreText.hide();
//             $(this).text("See More");
//         } else {
//             moreText.show();
//             $(this).text("See Less");
//         }
//     });
// });
// 
</script>

<script>
$(document).ready(function() {
    // Initialize totals
    updateTotals();
    $(document).on('input', '.variant-qty', function() {
        updateTotals();
    });
    $(document).on('input', '.main-product-qty', function() {
        updateTotals();
    });
    $(document).on('input', '.addon-qty', function() {
        updateTotals();
    });

    function updateTotals() {
        let productSubTotal = 0;

        // Calculate total for variants
        $('.variant').each(function() {
            const price = parseFloat($(this).data('price'));
            const quantity = parseInt($(this).find('.variant-qty').val()) || 0;
            const variantTotal = price * quantity;

            $(this).find('.variant-total').text('₹' + variantTotal.toFixed(2));
            productSubTotal += variantTotal;
        });

        // Calculate total for add-ons
        $('.addon').each(function() {
            const price = parseFloat($(this).data('price'));
            const quantity = parseInt($(this).find('.addon-qty').val()) || 0;
            const addonTotal = price * quantity;

            $(this).find('.addon-total').text('₹' + addonTotal.toFixed(2));
            productSubTotal += addonTotal;
        });

        // Get the main product quantity
        const mainProductQty = parseInt($('.main-product-qty').val()) || 1;
        const mainProductPrice = 0;
        const mainProductTotal = mainProductPrice * mainProductQty;
        productSubTotal += mainProductTotal;

        $('#total-price').text('₹' + productSubTotal.toFixed(2));
    }

    // Add button functionality
    $(document).on('click', '.add-btn', function() {

        //Find each variant total into hidden field when increment
        let $row = $(this).closest(".variant");
        // let $qtyInput = $row.find(".variant-qty");
        let $totalSpan = $row.find(".variant-total");
        let $hiddenTotal = $row.find(".variant-total-hidden");

        let variantValue = parseFloat($(this).data("varvalue")) || 0;


        let total = 1 * variantValue;


        let sum_variant_total = 0;
        $('.variant-total-hidden').each(function() {
            let variant_total = parseFloat($(this).val()) ||
                0; // Convert to float, default to 0 if NaN
            sum_variant_total += variant_total;
        });

        let total_stock = $('#total_stock').val(); //alert(total_stock);
        if (total_stock < sum_variant_total + total) {
            $('#validationModal').appendTo('body').modal('show');
            $('#validationModal .modal-body').html('Out of Stock');
            return false;
        } else {
            $totalSpan.text(total.toFixed(2));
            $hiddenTotal.val(total);
            const controlGroup = $(this).siblings('.quantity-group');
            controlGroup.removeClass('d-none');
            $(this).addClass('d-none');
            controlGroup.find('.variant-qty, .addon-qty').val(1).trigger('input');
        }
    });

    // Add button functionality
    $(document).on('click', '.add-btn1', function() {
        const controlGroup = $(this).siblings('.quantity-group1');
        controlGroup.removeClass('d-none');
        $(this).addClass('d-none');
        controlGroup.find('.variant-qty, .addon-qty').val(1).trigger('input');
    });

    // Add button functionality
    $(document).on('click', '.add-btn2', function() {
        const controlGroup = $(this).siblings('.quantity-group2');
        controlGroup.removeClass('d-none');
        $(this).addClass('d-none');
        controlGroup.find('.variant-qty, .addon-qty').val(1).trigger('input');
    });

    $(document).on('click', '.increment', function() {

        //Find each variant total into hidden field when increment
        let $row = $(this).closest(".variant");
        let $qtyInput = $row.find(".variant-qty");
        let $totalSpan = $row.find(".variant-total");
        let $hiddenTotal = $row.find(".variant-total-hidden");

        let quantity = parseInt($qtyInput.val()) || 0;
        let variantValue = parseFloat($(this).data("varvalue")) || 0;


        let total = quantity * variantValue + variantValue;

        let total_new = 1 * variantValue;
        // $totalSpan.text(total.toFixed(2));
        // $hiddenTotal.val(total);



        let sum_variant_total = 0;
        $('.variant-total-hidden').each(function() {
            let variant_total = parseFloat($(this).val()) ||
                0; // Convert to float, default to 0 if NaN
            sum_variant_total += variant_total;
        });


        let total_stock = $('#total_stock').val(); //alert(total_stock);
        if (total_stock < sum_variant_total + total_new) {
            $('#validationModal').appendTo('body').modal('show');
            $('#validationModal .modal-body').html('Out of Stock');
            return false;
        } else {
            $totalSpan.text(total.toFixed(2));
            $hiddenTotal.val(total);
        }

        var store_id = $('#store_id').val();
        const product_id = $(this).data('prodid');
        const variant_value = $(this).data('varvalue'); // Variant ID
        const variant_code = $(this).data('varcode'); // Variant Code
        const inputField = $(this).siblings('input');
        const currentQuantity = parseInt(inputField.val()) || 0; // Ensure it's an integer
        const newQuantity = currentQuantity + 1; // Incremented value

        $.ajax({
            url: '<?= base_url("cart/updateQuantity") ?>',
            method: 'POST',
            data: {
                product_id: product_id,
                store_id: store_id,
                variant_value: variant_value,
                variant_code: variant_code,
                quantity: newQuantity * variant_value
            },
            success: function(response) {
                if ($.trim(response) === 'success') {
                    inputField.val(newQuantity).trigger('input'); // Update only on success
                } else {
                    $('#validationModal').appendTo('body').modal('show');
                    $('#validationModal .modal-body').html(response);
                }
            }
        });
    });



    // Decrement button functionality
    $(document).on('click', '.decrement', function() {

        let sum_variant_total = 0;
        $('.variant-total-hidden').each(function() {
            let variant_total = parseFloat($(this).val()) ||
                0; // Convert to float, default to 0 if NaN
            sum_variant_total += variant_total;
        });


        const inputField = $(this).siblings('input');
        let currentVal = parseInt(inputField.val());
        if (currentVal > 1) {
            inputField.val(currentVal - 1).trigger('input');

            //Find each variant total into hidden field when decrement
            let $row = $(this).closest(".variant");
            let $price = 100;
            let $totalSpan = $row.find(".variant-total");
            let $hiddenTotal = $row.find(".variant-total-hidden");

            let quantity = parseInt(inputField.val()) || 0;
            let variantValue = parseFloat($(this).data("varvalue")) || 0;
            let total_new = quantity * price;
            let total = quantity * variantValue;
            //alert(total);
            $totalSpan.text(total_new.toFixed(2));
            $hiddenTotal.val(total);

        } else {
            inputField.val(0).trigger('input');
            //Find each variant total into hidden field when decrement
            let $row = $(this).closest(".variant");
            let $totalSpan = $row.find(".variant-total");
            let $hiddenTotal = $row.find(".variant-total-hidden");

            let quantity = parseInt(inputField.val()) || 0;
            let variantValue = parseFloat($(this).data("varvalue")) || 0;

            let total = quantity * variantValue;
            //alert(total);
            $totalSpan.text(total.toFixed(2));
            $hiddenTotal.val(total);

            const controlGroup = $(this).parent();
            controlGroup.addClass('d-none');
            controlGroup.siblings('.add-btn').removeClass('d-none');
        }
    });

    // Increment button functionality
    $(document).on('click', '.increment1', function() {
        const product_id = $(this).data('prodid');
        const quantity = $(this).siblings('input').val();
        const inputField = $(this).siblings('input');
        $.ajax({
            url: '<?= base_url("website/Products/checkStockAvailability") ?>',
            method: 'POST',
            data: {
                product_id: product_id,
                quantity: quantity
            },
            success: function(data) {
                if (data == 'success') {
                    inputField.val(parseInt(inputField.val()) + 1).trigger('input');
                } else {
                    $('#validationModal').appendTo('body').modal('show');
                    $('#validationModal .modal-body').html(data);
                }
            }
        });
    });

    // Decrement button functionality
    $(document).on('click', '.decrement1', function() {
        const inputField = $(this).siblings('input');
        let currentVal = parseInt(inputField.val());
        if (currentVal > 1) {
            inputField.val(currentVal - 1).trigger('input');
        } else {
            inputField.val(0).trigger('input');
            const controlGroup1 = $(this).parent();
            controlGroup1.addClass('d-none');
            controlGroup1.siblings('.add-btn1').removeClass('d-none');
        }
    });

    // Increment button functionality
    $(document).on('click', '.increment2', function() {
        const inputField = $(this).siblings('input');
        inputField.val(parseInt(inputField.val()) + 1).trigger('input');
    });

    // Decrement button functionality
    $(document).on('click', '.decrement2', function() {
        const inputField = $(this).siblings('input');
        let currentVal = parseInt(inputField.val());
        if (currentVal > 1) {
            inputField.val(currentVal - 1).trigger('input');
        } else {
            inputField.val(0).trigger('input');
            const controlGroup2 = $(this).parent();
            controlGroup2.addClass('d-none');
            controlGroup2.siblings('.add-btn2').removeClass('d-none');
        }
    });


});
</script>