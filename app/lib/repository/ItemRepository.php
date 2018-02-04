<?php
namespace Walltwisters\repository;

use Walltwisters\model\ProductSize;
use Walltwisters\model\Item;

require_once 'BaseRepository.php';
require_once 'model/Item.php';

class ItemRepository  extends BaseRepository {
    
    public function __construct() {
        parent::__construct("items", "Walltwisters\model\Item");
    }
    
    protected function getColumnNamesForInsert() {
        return ['size_id', 'material_id', 'print_technique_id', 'printer_id'];
    }
    
    protected function getColumnValuesForBind($item) {
        $size_id = $item->sizeId;
        $material_id = $item->materialId;
        $print_technique_id = $item->printTechniqueId;
        $printer_id = $item->printerId;

        return [['i', &$size_id], ['i', &$material_id], ['i', &$print_technique_id], ['i', &$printer_id],];
    }
    
    public function getItemSizesBy($material){
        $materialId = $material->id;
        $sql = ("SELECT DISTINCT i.size_id,
                s.sizes,
                s.name
                FROM items i
                INNER JOIN sizes s ON i.size_id = s.id
                WHERE i.material_id=?");
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $materialId);                                                              
        $res = $stmt->execute();
        $sizes = [];
        if ($res) {
            $stmt->bind_result($sizeId, $sizesName, $sDescription);
            while ($stmt->fetch()) {
                $sizes[] = ProductSize::create($sizesName, $sDescription, $sizeId);
            }
        }
        
        return $sizes;
        
    }
    
    public function getItemsByMaterialSizeId($item){
        $materialId = $item->materialId;
        $sizeId = $item->sizeId;
        $sql = ("SELECT id,
                size_id,
                material_id,
                print_technique_id,
                printer_id
                FROM items 
                WHERE material_id=? AND size_id=?");
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $materialId, $sizeId);                                                              
        $res = $stmt->execute();
        $items = [];
        if ($res) {
            $stmt->bind_result($itemId, $size_Id, $material_Id, $printTechniqueId, $printerId);
            while ($stmt->fetch()) {
                $itemOut = Item::create($size_Id, $material_Id, $printTechniqueId, $printerId, $itemId);
                $items[] = $itemOut;
            }
        }
        
        return $items;
    }
}