<?php
/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */

/**
 * @group ohrmWidget
 */
class ohrmReportWidgetAgeGroupTest extends PHPUnit\Framework\TestCase
{
    /**
     * @var ohrmReportWidgetAgeGroup
     */
    private $ohrmReportWidgetAgeGroup = null;

    protected function setUp(): void
    {
        $this->ohrmReportWidgetAgeGroup = $this->getMockBuilder(ohrmReportWidgetAgeGroup::class)
            ->setMethods(['configure'])
            ->getMock();
    }

    /**
     * @dataProvider generateWhereClausePartDataProvider
     * @param $value
     * @param $expected
     */
    public function testGenerateWhereClausePart($value, $expected)
    {
        $this->ohrmReportWidgetAgeGroup->setWhereClauseCondition($value['comparision']);
        $returnValue = $this->ohrmReportWidgetAgeGroup->generateWhereClausePart('fieldName', $value);
        $this->assertEquals($expected, $returnValue);
    }

    /**
     * @return Generator
     */
    public function generateWhereClausePartDataProvider()
    {
        // <
        yield [
            [
                'comparision' => '1',
                'value1' => '20',
                'value2' => ''
            ],
            "fieldName < '20'"
        ];

        // >
        yield [
            [
                'comparision' => '2',
                'value1' => '30',
                'value2' => ''
            ],
            "fieldName > '30'"
        ];

        // BETWEEN
        yield [
            [
                'comparision' => '3',
                'value1' => '20',
                'value2' => '30'
            ],
            "( fieldName BETWEEN '20' AND '30' )"
        ];
    }

    public function testGenerateWhereClausePartWithoutSetWhereClauseCondition()
    {
        $returnValue = $this->ohrmReportWidgetAgeGroup->generateWhereClausePart(
            'fieldName',
            [
                'comparision' => '1',
                'value1' => '20',
                'value2' => ''
            ]
        );
        $this->assertNull($returnValue);
    }

    /**
     * @dataProvider generateWhereClausePartWithSqlDataProvider
     * @param $value
     * @param $expected
     */
    public function testGenerateWhereClausePartWithSql($value, $expected)
    {
        $this->ohrmReportWidgetAgeGroup->setWhereClauseCondition($value['comparision']);
        $returnValue = $this->ohrmReportWidgetAgeGroup->generateWhereClausePart('fieldName', $value);
        $this->assertEquals($expected, $returnValue);
    }

    /**
     * @return Generator
     */
    public function generateWhereClausePartWithSqlDataProvider()
    {
        // <
        yield [
            [
                'comparision' => '1',
                'value1' => '1;DELETE FROM `hs_hr_employee` WHERE `hs_hr_employee`.`emp_number` = "1";',
                'value2' => ''
            ],
            'fieldName < \'1;DELETE FROM `hs_hr_employee` WHERE `hs_hr_employee`.`emp_number` = \"1\";\'',
        ];

        // >
        yield [
            [
                'comparision' => '2',
                'value1' => '1;DELETE FROM `hs_hr_employee` WHERE `hs_hr_employee`.`emp_number` = "1";',
                'value2' => ''
            ],
            'fieldName > \'1;DELETE FROM `hs_hr_employee` WHERE `hs_hr_employee`.`emp_number` = \"1\";\'',
        ];

        // BETWEEN
        yield [
            [
                'comparision' => '3',
                'value1' => '1;DELETE FROM `hs_hr_employee` WHERE `hs_hr_employee`.`emp_number` = "1";',
                'value2' => '30'
            ],
            '( fieldName BETWEEN \'1;DELETE FROM `hs_hr_employee` WHERE `hs_hr_employee`.`emp_number` = \"1\";\' AND \'30\' )'
        ];
    }
}
