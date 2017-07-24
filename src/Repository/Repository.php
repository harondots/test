<?php


namespace Test\Repository;

use PDO;
use PDOStatement;
use Test\Entity\OptionValue;
use Test\Entity\Product;
use Test\Entity\ProductOption;

class Repository
{
    protected $db;

    /**
     * Repository constructor.
     * @param PDO $db
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getOptionsValues()
    {
        $query = $this->db->prepare('
            SELECT
              po.name,
              po.id option_id,
              ov.id value_id,
              ov.value
            FROM product_options po, option_values ov
            WHERE po.id = ov.option_id  
        ');
        $query->execute();

        $result = [];
        $optionsValues = $query->fetchAll();
        foreach ($optionsValues as $optionsValue) {
            $optionName = $optionsValue['name'];
            if (!array_key_exists($optionName, $result)) {
                $productOption = new ProductOption($optionName);
                $productOption->setId($optionsValue['option_id']);
                $result[$optionName] = $productOption;
            } else {
                $productOption = $result[$optionName];
            }

            $optionValue = new OptionValue($productOption, $optionsValue['value']);
            $optionValue->setId($optionsValue['value_id']);
            $productOption->addValue($optionValue);
        }

        return $result;
    }

    /**
     * @param $conditions
     * @return Product[]
     */
    public function findProducts($conditions)
    {
        $sql = $this->buildQuery($conditions);
        $query = $this->db->prepare($sql);
        $query->execute();

        return $this->parseProducts($query);
    }

    /**
     * @param $conditions
     * @return string
     */
    private function buildInnerQuery($conditions): string
    {
        if (empty($conditions)) {
            $innerSql = 'SELECT id FROM product';
        } else {
            $optionsIds = $this->quoteArray(array_keys($conditions));

            $optionsValues = [];
            foreach ($conditions as $condition) {
                $optionsValues = array_merge($optionsValues, $condition);
            }
            $optionsValues = $this->quoteArray($optionsValues);

            $innerSql = '
                SELECT
                  p.id
                FROM product p
                  INNER JOIN product_options_values pov ON p.id = pov.product_id
                  INNER JOIN option_values ov ON pov.value_id = ov.id
                  INNER JOIN product_options po ON pov.option_id = po.id
                WHERE
                  pov.option_id IN (' . implode(',', $optionsIds) . ')
                  AND pov.value_id IN (' . implode(',', $optionsValues) . ')
                GROUP BY id
                HAVING count(*) = ' . count($conditions);
        }

        return $innerSql;
    }

    /**
     * @param PDOStatement $query
     * @return array
     */
    private function parseProducts($query): array
    {
        $result = [];
        $q = $query->fetchAll();
        foreach ($q as $item) {
            $id = $item['id'];
            if (array_key_exists($id, $result)) {
                $product = $result[$id];
            } else {
                $product = new Product();
                $product
                    ->setId($id)
                    ->setPrice($item['price'])
                    ->setModel($item['model']);
                $result[$id] = $product;
            }

            $productOption = new ProductOption($item['name']);
            $productOption
                ->setId($item['option_id'])
                ->setUnit($item['unit']);
            $product->addOption($productOption);

            $optionValue = new OptionValue($productOption, $item['value']);
            $optionValue->setId($item['value_id']);

            $productOption->addValue($optionValue);

        }

        return $result;
    }

    /**
     * @param $conditions
     * @return string
     */
    private function buildQuery($conditions): string
    {
        $innerSql = $this->buildInnerQuery($conditions);

        $sql = '
        SELECT
          p.id id,
          p.model model,
          pov.option_id option_id,
          pov.value_id value_id,
          po.unit unit,
          p.model model,
          p.price price,
          value,
          name
        FROM product p
          INNER JOIN product_options_values pov ON p.id = pov.product_id
          INNER JOIN option_values ov ON pov.value_id = ov.id
          INNER JOIN product_options po ON pov.option_id = po.id
        WHERE p.id IN(' . $innerSql . ')';

        return $sql;
    }

    private function quoteArray(array $optionsValues)
    {
        array_walk($optionsValues, function (&$a, $b) {
            if (is_array($a)) {
                foreach ($a as &$item) {
                    $item = (int)$item;
                    if (0 === $item) {
                        throw new \RuntimeException("Неправильное значение параметра.");
                    }
                }
            } else {
                $a = (int)$a;
                if (0 === $a) {
                    throw new \RuntimeException("Неправильное значение параметра.");
                }
            }
        });

        return $optionsValues;
    }
}