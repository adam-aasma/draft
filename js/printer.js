function addItem(e){
    
    var nuItems =document.getElementById('numberItem').value;
  
    for(i=1; i<nuItems; i++){
        var item = '<fieldset>'
                       + '<legend>Item</legend>'
                       + '<table>'
                       +   '<tr>'
                       +      '<td>'
                       +         "<label for='adding-paper'>paper</label>"
                       +        "<input type='text' name='paper[]' id='adding-paper'>"
                       +       "</td>"
                       +       "<td>"
                       +           " <label for='adding-size'>size</label>"
                       +            "<input type='text' name='size[]' id='adding-size'>"
                       +        "</td>"
                       +    "</tr>"
                       +    "<tr>"
                       +        "<td colspan='2'>"
                       +           "<label for='adding-technique'>print technique</label>"
                       +           "<input type='text' name='technique[]' id='adding-technique'>"
                       +        "</td>"
                       +    "</tr>"
                       +    "<tr>"
                       +        "<td>"
                       +            "<label for='adding-paper-price'>paper price</label>"
                       +            "<input type='text' name='paperprice[]' id='adding-paper-price'>"
                       +        "</td>"
                       +        "<td>"
                       +            "<label for='adding-technique-price'>technique price</label>"
                       +            "<input type='text' name='techniqueprice[]' id='adding-technique-price'>"
                       +        "</td>"
                       +    "</tr>"
                       +    "<tr>"
                       +        "<td>"
                       +            "<label for='labour-price'>labour price</label>"
                       +            "<input type='text' name='labourprice[]' id='labour-price'>"
                       +        "</td>"
                       +        "<td>"
                       +            "<label for='total-price'> total price</label>"
                       +            "<input type='text' name='total price[]' id='total-price'>"
                       +        "</td>"
                       +    "</tr>"
                       +"</table>"
                   +"</fieldset>";

           var anchor = document.getElementById('firstitem');
           anchor.insertAdjacentHTML('afterend', item);
    }
    e.preventDefault();


    
}

var elitem = document.getElementById('additem');
elitem.addEventListener('click', function(e) { addItem(e);}, false);


