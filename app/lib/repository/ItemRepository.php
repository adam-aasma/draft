<?php
namespace Walltwisters\repository;

use Walltwisters\model\ProductSize;
use Walltwisters\model\ItemExtended;

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
        $stmt = self::$conn->prepare($sql);
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
        $stmt = self::$conn->prepare($sql);
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
    
    public function getCountryItems($countryIds) {
        $query = "select p.country_id, m.id as materialId, m.material, s.id as sizesId, s.sizes from printers p
inner join items i on i.printer_id = p.id
inner join materials m on m.id = i.material_id
inner join sizes s on s.id = i.size_id";
        $stmt = $this->createStatementForInClause($query, "country_id", $countryIds, 'i');
        $res = $stmt->execute();
        if (!$res) {
            throw new \Exception($stmt->error);
        }
        $stmt->bind_result($countryId, $materialId, $material, $sizesId, $sizes);
        $countryItemsKeyed = [];
        while ($stmt->fetch()) {
            if (!array_key_exists($countryId, $countryItemsKeyed)) {
                $countryItemsKeyed[$countryId] = [];
            }
            if (!array_key_exists($materialId, $countryItemsKeyed[$countryId])) {
                $countryItemsKeyed[$countryId][$materialId] = ['materialId' => $materialId, 'material' => $material, 'sizes' => []];
            }
            if (empty($countryItemsKeyed[$countryId][$materialId]['sizes']) || 
                    !array_key_exists($sizesId, $countryItemsKeyed[$countryId][$materialId]['sizes'])) {
                $countryItemsKeyed[$countryId][$materialId]['sizes'][$sizesId] = ['sizeId' => $sizesId, 'size' => $sizes];
            }
        }
        
        // Remove keys
        $countryItems = [];
        foreach ($countryItemsKeyed as $countryId => $countryItemMaterials) {
            $countryItems[$countryId] = [];
            foreach($countryItemMaterials as $materialId => $materialInfo) {
                $materialInfo2 = ['materialId' => $materialInfo['materialId'], 'material' => $materialInfo['material']];
                foreach($materialInfo['sizes'] as $sizeId => $sizeInfo) {
                    $materialInfo2['sizes'][] = $sizeInfo;
                }
                $countryItems[$countryId][] = $materialInfo2;
            }
        }
        
        return $countryItems;
    }
}