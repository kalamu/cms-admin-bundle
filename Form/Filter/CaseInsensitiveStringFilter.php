<?php

/*
 * This file is part of the kalamu/cms-admin-bundle package.
 *
 * (c) ETIC Services
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kalamu\CmsAdminBundle\Form\Filter;

/**
 * @see https://gist.github.com/dbu/9524776
 */

use Sonata\AdminBundle\Form\Type\Filter\ChoiceType;
use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Sonata\DoctrineORMAdminBundle\Filter\StringFilter;

class CaseInsensitiveStringFilter extends StringFilter
{
    /**
     * {@inheritdoc}
     */
    public function filter(ProxyQueryInterface $queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data)) {
            return;
        }
        $data['value'] = trim($data['value']);
        if (strlen($data['value']) == 0) {
            return;
        }
        $data['type'] = !isset($data['type']) ?  ChoiceType::TYPE_CONTAINS : $data['type'];
        $operator = $this->getOperator((int) $data['type']);
        if (!$operator) {
            $operator = 'LIKE';
        }
        // c.name > '1' => c.name OPERATOR :FIELDNAME
        $parameterName = $this->getNewParameterName($queryBuilder);
                                          // This is the first difference
                                          //        |
                                          //        V
        $this->applyWhere($queryBuilder, sprintf('lower(%s.%s) %s :%s', $alias, $field, $operator, $parameterName));
        if ($data['type'] == ChoiceType::TYPE_EQUAL) {
                                          // This is the second difference
                                          //                |
                                          //                V
            $queryBuilder->setParameter($parameterName, mb_strtolower($data['value'], 'UTF-8'));
        } else {
            $queryBuilder->setParameter($parameterName, sprintf($this->getOption('format'), strtolower($data['value'])));
        }
    }
    /**
     * exact copy-paste because private method
     */
    private function getOperator($type)
    {
        $choices = array(
            ChoiceType::TYPE_CONTAINS         => 'LIKE',
            ChoiceType::TYPE_NOT_CONTAINS     => 'NOT LIKE',
            ChoiceType::TYPE_EQUAL            => '=',
        );
        return isset($choices[$type]) ? $choices[$type] : false;
    }
}