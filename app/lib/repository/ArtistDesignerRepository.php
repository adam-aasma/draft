<?php

namespace Walltwisters\lib\repository;


class ArtistDesignerRepository  extends BaseRepository {
    
    public function __construct() {
        parent::__construct("artist_designer", "Walltwisters\lib\model\artistDesigner");
    }
    
    protected function getColumnNamesForInsert() {
        return ['artist_designer', 'description'];
    }
    
    protected function getColumnValuesForBind($artistDesigner) {
        $artist_designer = $artistDesigner->artistDesigner;
        $description = $artistDesigner->description;
        
    return [['s', &$artist_designer],['s', &$description]];
    }
    
   
    public function getAllArtistDesigners() {
        return $this->getAll();
    }
    
    public function UpdateArtistDesigner($artist, $artistId) {
        $sql = "UPDATE artist_designer SET artist_designer = ? WHERE id = ?;";
        $stmt = self::$conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("SQL syntax: " . $sql);
        }
        $stmt->bind_param("si", $artist_name, $artist_id);
        $artist_name = $artist;
        $artist_id = $artistId;
        $res = $stmt->execute();
        if (!$res) {
           throw new Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
    }


}
