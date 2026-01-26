
    let carts = [] ;

    $(document).ready(function(){
        fetchCarts() ;
    }) ;

    function storeToCart(meal_id){
        $.ajax({
            url : "api/cart" ,
            method : "POST" ,
            headers : {'Accept' : 'application/json'} ,
            data : {
                user_id : `{{ auth()->user()->id ?? null }}` ,
                quantity : 1 ,
                meal_id : meal_id
            },
            success : function(){
                fetchCarts() ;
                toastr.success('تم إدخال العنصر الي السلة') ;
            },
            error: function(xhr){
                console.log(xhr.responseJSON?.message);
            }
        }) ;
    }

    function fetchCarts(){
        $.ajax({
            url :`carts` ,
            method : "GET" ,
            headers : {
                'Accept' : 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success : function(res){
                carts = res.data ;
                updateUI(carts) ;
            },
            error : function(){

            } ,
        }) ;
    }

    function updateUI() {
        const itemsCont = document.getElementById('cartItems');
        const cartTotal = document.getElementById('cartTotal');
        const cartCount = document.getElementById('cart-count');

        let total = 0;
        let count = 0;

        if (carts.length === 0) {
            itemsCont.innerHTML = '<p class="empty-msg">سلتك بانتظار أشهى المأكولات</p>';
        } else {

            itemsCont.innerHTML = '';
            carts.forEach(cart => {
                total += cart.meal.price * cart.quantity;
                count += cart.quantity ;

                itemsCont.innerHTML += `
                    <div class="cart-item">
                        <img src="${cart.meal.image_url}" alt="${cart.meal.title}" class="cart-item-img">
                        <div class="cart-item-info">
                            <h4>${cart.meal.title}</h4>
                            <p>${cart.meal.price} ج.م</p>
                        </div>
                        <div class="qty-controls">
                            <button onclick="changeQty(${cart.id}, -1)">-</button>
                            <span>${cart.quantity}</span>
                            <button onclick="changeQty(${cart.id}, 1)">+</button>
                        </div>
                    </div>
                `;
            });
        }

        // تحديث الأرقام النهائية
        cartTotal.innerText = total;
        cartCount.innerText = count;
    }

    function changeQty(id, amt) {
        const item = carts.find(cart => cart.id === id);
        if (!item) return;

        $.ajax({
            url:`api/cart/${id}`,
            method:'PUT' ,
            headers : {
                'Accept' : 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                quantity: amt ,
            } ,
            success : function(){
                fetchCarts() ;
            },
            error: function(xhr){
                alert(xhr.responseJSON?.message) ;
            }
        }) ;
        if(item.quantity <= 0) {
            cart = carts.filter(i => i.id !== id);
        }
        updateUI();
    }
