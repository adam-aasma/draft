<?php
namespace Walltwisters\service;



class PrinterService extends BaseService {
    
    public function __construct($repositoryFactory) {
        parent::__construct($repositoryFactory);
    }
    
    public function addPrinter($printer){
        $printerRepository = $this->repositoryFactory->getRepository('printerRepository');
        $printer = $printerRepository->create($printer, true);
        return $printer->id;
    }
    
    public function addItem($items, $printerId, $itemPrices){
        $itemIds = [];
        $index = 0;
        foreach ($items as $item){
            $sizes = $this->getAllSizes();
            $papers = $this->getAllMaterials();
            $techniques = $this->getAllPrintTechniques();
            $modelItem = new Walltwisters\model\Item();
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
            $productSizeRepo = $this->repositoryFactory->getRepository('productSizeRepository');
            $sobj= $productSizeRepo->create(WallTwisters\model\ProductSize::create($item->size), true);
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
            $productTechRepo = $this->repositoryFactory->getRepository('productPrintTechniqueRepository');
            $tobj = $productTechRepo->create(Walltwisters\model\ProductPrintTechnique::create($item->technique), true);
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
            $productMaterialRepo = $this->repositoryFactory->getRepository('productMaterialRepository');
            $mobj = $productMaterialRepo->create(Walltwisters\model\ProductMaterial::create($item->paper), true);
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
