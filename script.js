$(document).ready(function () {
  console.log("document ready");

  let chosenItems = new Set();
  $('.addcartbtn').click(function () {
    const CHOSEN_ITEM_ID = $(this).val();
    console.log('add to cart button clicked! ID = ' + $(this).val());
    chosenItems.add(CHOSEN_ITEM_ID);
    console.log(' chosen items: ' + chosenItems);
    // $(this).attr('disabled', true);
    // $(this).text("Added");
    for (var c of chosenItems.values()) {
      console.log(' > ' + c);
    }
  });

  $('#buybtn').click(function () {
    console.log('buy button clicked! ' + JSON.stringify(Array.from(chosenItems)));
    $('#chosenitems').val(JSON.stringify(Array.from(chosenItems)));
  });
});
