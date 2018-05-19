<?php
namespace Walltwisters\lib\service;

use Walltwisters\lib\model\Printer;
use Walltwisters\lib\model\ItemSize;
use Walltwisters\lib\model\ItemMaterial;
use Walltwisters\lib\model\ItemPrintTechnique;
use Walltwisters\lib\model\item;

class PrinterService extends BaseService {
    
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
        
    }
    
    public function initializePrinter($countryId, $userId){
        $printer = Printer::create('???', '???', '???', '???', $countryId, $userId);
        return $this->addPrinter($printer)->id;
    }
    
    public function addPrinter($printer){
        $printerRepo = $this->repositoryFactory->getRepository('printerRepository');
        return $printerRepo->create($printer, true);
        
    }
    
    public function updatePrinter($countryId, $printerId, $name, $email, $telephone, $contact, $userId) {
        $printer = Printer::create($name, $email, $telephone, $contact, $countryId, $userId, $printerId);
        $printerRepo = $this->repositoryFactory->getRepository('printerRepository');
        $obj = $printerRepo->createOrUpdate($printer);
        
        return $obj;
    }
    
    public function updateItem($printerId, $size, $technique, $material, $itemId = null){
        $iteration = [
            'Walltwisters\lib\model\ItemSize' => $size, 
            'Walltwisters\lib\model\ItemPrintTechnique' => $technique, 
            'Walltwisters\lib\model\ItemMaterial' => $material
            ];
        $objs = [];
        foreach($iteration as $obj => $string) {
           $objs[] = $this->getValueAndReturnObj($obj, $string);
        }
        
        $ids = $this->getOrCreateReturnIds($objs);
        $item = Item::create($ids['sizeId'], $ids['materialId'], $ids['techniqueId'], $printerId, $itemId);
        $repo = $this->repositoryFactory->getRepository('itemRepository');
        
        $res = $repo->createOrUpdate($item, true);
        
        return $res->id;
    }
    
    private function getOrCreateReturnIds($objs){
        $ids = ['sizeId'=> null, 'techniqueId' => null, 'materialId' => null];
        foreach($objs as $obj){
            if ($obj instanceof ItemSize && strlen($obj->sizes) !== 0){
                $repo = $this->repositoryFactory->getRepository('itemSizeRepository');
                $sizesObjs = $repo->ifRowExistsReturnElseCreate($obj);
                $ids['sizeId'] = is_array($sizesObjs) ? $sizesObjs[0]->id : $sizesObjs->id;
            }
            if ($obj instanceof ItemMaterial && strlen($obj->material) !== 0){
                $repo = $this->repositoryFactory->getRepository('itemMaterialRepository');
                $materialObjs =  $repo->ifRowExistsReturnElseCreate($obj);
                $ids['materialId'] = is_array($materialObjs) ?  $materialObjs[0]->id : $materialObjs->id;
            }
            if ($obj instanceof ItemPrintTechnique && strlen($obj->technique) !== 0){
                $repo = $this->repositoryFactory->getRepository('itemPrintTechniqueRepository');
                $techniqueObjs = $repo->ifRowExistsReturnElseCreate($obj);
                $ids['techniqueId'] = is_array($techniqueObjs) ? $techniqueObjs[0]->id : $techniqueObjs->id;
            }
        }
        
        return $ids;
    }
    
    private function getValueAndReturnObj($obj, $string){
        return $obj::create($string);
    }
    
    public function addItem($items, $printerId, $itemPrices){
        $itemIds = [];
        $index = 0;
        foreach ($items as $item){
            $sizes = $this->getAllSizes();
            $papers = $this->getAllMaterials();
            $techniques = $this->getAllPrintTechniques();
            $modelItem = new Walltwisters\lib\model\Item();
            $modelItem->printerId = $printerId;
            $sizeId = 0;
            $techniqueId = 0;
            $paperId = 0;
            foreach ($sizes as $size){
                if (strtoupper((string) $size->sizes) === strtoupper((string) $item->size)){
                    $sizeId = $size->id;
                    $modelItem->sizeId = $sizeId;
                    break;
                }
            }
            if (!$sizeId){
            $productSizeRepo = $this->repositoryFactory->getRepository('ItemSizeRepository');
            $sobj= $productSizeRepo->create(Walltwisters\lib\model\ItemSize::create($item->size), true);
            $modelItem->sizeId = $sobj->id;
            }
            foreach ($techniques as $technique){
                if (strtoupper($technique->technique) === strtoupper($item->technique)){
                    $techniqueId= $technique->id;
                    $modelItem->printTechniqueId = $techniqueId;
                    break;
                }
            }
            if (!$techniqueId){
            $productTechRepo = $this->repositoryFactory->getRepository('ItemPrintTechniqueRepository');
            $tobj = $productTechRepo->create(Walltwisters\lib\model\ItemPrintTechnique::create($item->technique), true);
            $modelItem->printTechniqueId = $tobj->id;
            }
            foreach ($papers as $paper){
                if (strtoupper($paper->material) === strtoupper($item->paper)){
                    $paperId = $paper->id;
                    $modelItem->materialId = $paperId;
                    break;
                }
            }
            if (!$paperId){
            $ItemMaterialRepo = $this->repositoryFactory->getRepository('ItemMaterialRepository');
            $mobj = $ItemMaterialRepo->create(Walltwisters\lib\model\ItemMaterial::create($item->paper), true);
            $modelItem->materialId = $mobj->id;
            }
            $itemRepo = $this->repositoryFactory->getRepository('itemRepository');
            $iobj = $itemRepo->create($modelItem, true);
            $itemPrices[$index]->itemId = $iobj->id;
            $index += 1;
        }
        
        $this->addItemPrices($itemPrices);
    }
    
    private function addItemPrices($itemPrices){
        foreach ($itemPrices as $itemPrice){
            $this->repositoryFactory->getRepository('itemPriceRepository')->create($itemPrice);
        }
        
    }
    
}
