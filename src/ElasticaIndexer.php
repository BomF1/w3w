<?php

class ElasticaIndexer
{
    const BULK_LIMIT = 100;

    /**
     * @param Zend_Db_Select $select SQL dotaz vracejici zaznamy k zaindexovani
     * @param \Elastica\Index $index elasticsearch index kam zapisujeme data z databaze
     * @return int pocet zaindexovanych zaznamu
     */
    public function updateDocuments(Zend_Db_Select $select, \Elastica\Index $index)
    {
        $index->setSettings(['index' => ['refresh_interval' => '-1']]);
        $stmt = $select->query(Zend_Db::FETCH_ASSOC);
        $bulkData = [];
        $numberOfDocumentsIndexed = 0;
        while ($data = $stmt->fetch()) {
            $id = (int) $data['id'];
            unset($data['id']);
            $document = new \Elastica\Document($id, $data);
            $bulkData[] = $document;

            if (count($bulkData) >= self::BULK_LIMIT) {
                $index->getType('products')->addDocuments($bulkData);
                $numberOfDocumentsIndexed++;
            }
        }

        // zaindexovať zvyšok dát ak by ich bolo napr. 340 a limit je 100 tak while prejde 3 krát len ešte ostanú nezaindexované 40
        if (!empty($bulkData)) {
            $index->getType('products')->addDocuments($bulkData);
            $numberOfDocumentsIndexed++;
        }

        $stmt->closeCursor();
        $index->flush();
        $index->refresh();
        $index->optimize();
        $index->setSettings(['index' => ['refresh_interval' => '1s']]);
        return $numberOfDocumentsIndexed;
    }
}
