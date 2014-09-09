<?php

/**
 * Mno Item Class
 */
class MnoSoaItem extends MnoSoaBaseItem
{
    protected $_local_entity_name = "products";
    
    protected function pushId() {
        $this->_log->debug(__FUNCTION__ . " start");
        $id = $this->getLocalEntityIdentifier();
        
        if (!empty($id)) {
            $mno_id = $this->getMnoIdByLocalId($id);

            if ($this->isValidIdentifier($mno_id)) {
                $this->_log->debug(__FUNCTION__ . " this->getMnoIdByLocalId(id) = " . json_encode($mno_id));
              $this->_id = $mno_id->_id;
            }
        }
        $this->_log->debug(__FUNCTION__ . " end");
    }
    
    protected function pullId() {
      $this->_log->debug(__FUNCTION__ . " start " . $this->_id);
        
      if (!empty($this->_id)) {
          $local_id = $this->getLocalIdByMnoId($this->_id);
          $this->_log->debug(__FUNCTION__ . " this->getLocalIdByMnoId(this->_id) = " . json_encode($local_id));
          
          if ($this->isValidIdentifier($local_id)) {
            $this->_log->debug(__FUNCTION__ . " is STATUS_EXISTING_ID");
            $this->_local_entity = CRMEntity::getInstance("Products");
            $this->_local_entity->retrieve_entity_info($local_id->_id,"Products");
            vtlib_setup_modulevars("Products", $this->_local_entity);
            $this->_local_entity->id = $local_id->_id;
            $this->_local_entity->mode = 'edit';
            return constant('MnoSoaBaseEntity::STATUS_EXISTING_ID');
          } else if ($this->isDeletedIdentifier($local_id)) {
            $this->_log->debug(__FUNCTION__ . " is STATUS_DELETED_ID");
            return constant('MnoSoaBaseEntity::STATUS_DELETED_ID');
          } else {
            $this->_local_entity = new Products();
            $this->pullName();
            $this->pullCode();
            $this->pullDescription();
            $this->pullType();
            $this->pullStatus();
            $this->pullUnit();
            $this->pullSalePrice();
            $this->pullPurchasePrice();

          return constant('MnoSoaBaseEntity::STATUS_NEW_ID');
          }
      }
      $this->_log->debug(__FUNCTION__ . " return STATUS_ERROR");
      return constant('MnoSoaBaseEntity::STATUS_ERROR');
    }
    
    protected function pushName() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_name = $this->push_set_or_delete_value($this->_local_entity->column_fields['productname']);
        $this->_log->debug(__FUNCTION__ . " end");
    }
    
    protected function pullName() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_local_entity->column_fields['productname'] = $this->pull_set_or_delete_value($this->_name);
        $this->_log->debug(__FUNCTION__ . " end");
    }

    protected function pushCode() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_code = $this->push_set_or_delete_value($this->_local_entity->column_fields['productcode']);
        $this->_log->debug(__FUNCTION__ . " end");
    }
    
    protected function pullCode() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_local_entity->column_fields['productcode'] = $this->pull_set_or_delete_value($this->_code);
        $this->_log->debug(__FUNCTION__ . " end");
    }

    protected function pushDescription() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_description = $this->push_set_or_delete_value($this->_local_entity->column_fields['description']);
        $this->_log->debug(__FUNCTION__ . " end");
    }
    
    protected function pullDescription() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_local_entity->column_fields['description'] = $this->pull_set_or_delete_value($this->_description);
        $this->_log->debug(__FUNCTION__ . " end");
    }

    protected function pushStatus() {
        $this->_log->debug(__FUNCTION__ . " start");
        $status = 'ACTIVE';
        // In vtiger, discontinued=0 or discontinued='' means the product is inactive
        $field_disc = $this->_local_entity->column_fields['discontinued'];
        if($field_disc == "" || $field_disc == "0") {
          $status = 'INACTIVE';
        }
        $this->_status = $status;
        $this->_log->debug(__FUNCTION__ . " end");
    }
    
    protected function pullStatus() {
        $this->_log->debug(__FUNCTION__ . " start");
        $discontinued = 1;
        if($this->pull_set_or_delete_value($this->_status) == 'INACTIVE') {
          $discontinued = 0;
        }
        $this->_local_entity->column_fields['discontinued'] = $discontinued;
        $this->_log->debug(__FUNCTION__ . " end");
    }

    protected function pushType() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_type = $this->push_set_or_delete_value($this->_local_entity->column_fields['productcategory']);
        $this->_log->debug(__FUNCTION__ . " end");
    }
    
    protected function pullType() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_local_entity->column_fields['productcategory'] = $this->pull_set_or_delete_value($this->_type);
        $this->_log->debug(__FUNCTION__ . " end");
    }

    protected function pushUnit() {
        $this->_log->debug(__FUNCTION__ . " start");
        // TODO: What is unit?
        $this->_log->debug(__FUNCTION__ . " end");
    }
    
    protected function pullUnit() {
        $this->_log->debug(__FUNCTION__ . " start");
        // TODO: What is unit?
        $this->_log->debug(__FUNCTION__ . " end");
    }

    protected function pushSalePrice() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_sale_price = $this->push_set_or_delete_value($this->_local_entity->column_fields['unit_price']);
        $this->_log->debug(__FUNCTION__ . " end");
    }
    
    protected function pullSalePrice() {
        $this->_log->debug(__FUNCTION__ . " start");
        $this->_local_entity->column_fields['unit_price'] = $this->pull_set_or_delete_value($this->_sale_price);
        $this->_log->debug(__FUNCTION__ . " end");
    }

    protected function pushPurchasePrice() {
      // DO NOTHING
    }
    
    protected function pullPurchasePrice() {
      // DO NOTHING
    }
    
    protected function pushEntity() {
      // DO NOTHING
    }
    
    protected function pullEntity() {
      // DO NOTHING
    }

    protected function saveLocalEntity($push_to_maestrano) {
      $this->_local_entity->save("Products", '', $push_to_maestrano);
    }
    
    public function getLocalEntityIdentifier() {
      return $this->_local_entity->id;
    }
}

?>