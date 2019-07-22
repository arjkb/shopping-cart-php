$(document).ready(function () {
  console.log("document ready");

  let chosenItems = new Set();
  $('.addcartbtn').click(function () {
    const PAYLOAD = $(this).val().split(';');
    const CHOSEN_ITEM_ID = PAYLOAD[0];
    const CUSTOMER_ID = PAYLOAD[1];
    console.log('add to cart button clicked! ID = ' + $(this).val());
    // chosenItems.add(CHOSEN_ITEM_ID);
    // console.log(' chosen items: ' + chosenItems);
    // // $(this).attr('disabled', true);
    // // $(this).text("Added");
    // for (var c of chosenItems.values()) {
    //   console.log(' > ' + c);
    // }

    $.ajax({
      url: 'index.php',
      type: 'POST',
      data: {
        customer_id: CUSTOMER_ID,
        addcartbtn: CHOSEN_ITEM_ID,
      },
      success: function(msg) {
        // alert('Attempt to add to cart!');
      }
    });
  });

  $('#buybtn').click(function () {
    console.log('buy button clicked! ' + JSON.stringify(Array.from(chosenItems)));
    $('#chosenitems').val(JSON.stringify(Array.from(chosenItems)));
  });
});
