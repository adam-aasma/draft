<?php
namespace Walltwisters\data;

require_once 'model/ItemPrice.php';

class ItemPriceRepository extends BaseRepository {
    
    public function __construct() {
        parent::__construct("item_price", "Walltwisters\model\ItemPrice");
    }
   
    protected function getColumnNamesForInsert() {
        return ['item_id', 'paper_price', 'technique_price', 'labour_price', 'total_price'];
    }
    
    protected function getColumnValuesForBind($itemprice) {
        $item_id = $itemprice->itemId;
        $paper_price = $itemprice->paperPrice;
        $technique_price = $itemprice->techniquePrice;;
        $labour_price = $itemprice->labourPrice;
        $total_price = $itemprice->totalPrice;
       
        return [['i', &$item_id], ['i', &$paper_price], ['i', &$technique_price], ['i', &$labour_price], ['i', &$total_price]];
    }
    
}
