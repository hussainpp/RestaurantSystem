import './bootstrap';

Echo.channel('orders')
    .listen('CreatedOrder', (e) => {
        console.log(e.order.name);
    });