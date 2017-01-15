<?php

/*
 *   Copyright 2008-2015 Maarch
 *
 *   This file is part of Maarch Framework.
 *
 *   Maarch Framework is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Maarch Framework is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Maarch Framework. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * @brief Batch to fill the stack
 *
 * @file
 * @author  Laurent Giovannoni  <dev@maarch.org>
 * @date $date$
 * @version $Revision$
 * @ingroup life_cycle
 */

/**
 * *****   LIGHT PROBLEMS without an error semaphore
 *  101 : Configuration file missing
 *  102 : Configuration file does not exist
 *  103 : Error on loading config file
 *  104 : SQL Query Error
 *  105 : a parameter is missin
 *  106 : Maarch_CLITools is missing
 *  107 : Stack full for the policy and the cycle requested
 *  108 : Problem with the php include path
 *  109 : An instance of the batch for the required policy and cyle is already
 *        in progress
 *  110 : Problem with collection parameter
 *  111 : No resource found
 * ****   HEAVY PROBLEMS with an error semaphore
 *  11  : Cycle not found
 *  12  : Previous cycle not found
 *  13  : Error persists
 *  14  : Cycle step not found
 */

date_default_timezone_set('Europe/Paris');
// load the config and prepare to process
//include('load_fill_stack.php');
include('load_process_stack.php');
/******************************************************************************/
/* beginning */
$state = 'CONTROL_STACK';
while ($state <> 'END') {
    if (isset($GLOBALS['logger'])) {
        $GLOBALS['logger']->write('STATE:' . $state, 'INFO');
    }
    switch ($state) {
        /**********************************************************************/
        /*                          CONTROL_STACK                             */
        /* Checking if the stack is full                                      */
        /**********************************************************************/
        case 'CONTROL_STACK' :
            $query = "select * from " . _LC_STACK_TABLE_NAME
                   . " where policy_id = ? and cycle_id = ? and regex = ?";
            $stmt = Bt_doQuery(
                $GLOBALS['db'], 
                $query, 
                array($GLOBALS['policy'], $GLOBALS['cycle'], $GLOBALS['regExResId'])
            );
            if ($stmt->rowCount() > 0) {
                Bt_exitBatch(107, 'WARNING stack is full for policy:'
                             . $GLOBALS['policy'] . ', cycle:'
                             . $GLOBALS['cycle'] . ', regex:'
                             . $GLOBALS['regExResId']);
                break;
            }
            $state = 'GET_STEPS';
            break;
        /**********************************************************************/
        /*                          GET_STEPS                                 */
        /* Get the list of cycle steps                                        */
        /**********************************************************************/
        case 'GET_STEPS' :
            $query = "select * from " . _LC_CYCLE_STEPS_TABLE_NAME
                   . " where policy_id = ? and cycle_id = ?";
            $stmt = Bt_doQuery(
                $GLOBALS['db'], 
                $query, 
                array(
                    $GLOBALS['policy'], 
                    $GLOBALS['cycle']
                )
            );
            if ($stmt->rowCount() == 0) {
                Bt_exitBatch(14, 'Cycle Steps not found');
                break;
            } else {
                while ($stepsRecordset = $stmt->fetchObject()) {
                    array_push(
                        $GLOBALS['steps'],
                        array('cycle_step_id' => $stepsRecordset->cycle_step_id)
                    );
                }
            }
            $state = 'SELECT_RES';
            break;
        /**********************************************************************/
        /*                          SELECT_RES                                */
        /* Selects candidates from each step of the cycle                     */
        /**********************************************************************/
        case 'SELECT_RES' :
            $orderBy = '';
            // get the where clause of the cycle
            $query = "select * from " . _LC_CYCLES_TABLE_NAME
                   . " where policy_id = ? and cycle_id = ?";
            $stmt = Bt_doQuery(
                $GLOBALS['db'], 
                $query, 
                array(
                    $GLOBALS['policy'], 
                    $GLOBALS['cycle']
                )
            );
            if ($stmt->rowCount() > 0) {
                $cycleRecordset = $stmt->fetchObject();
            } else {
                Bt_exitBatch(11, 'cycle not found for policy:'
                             . $GLOBALS['policy'] . ', cycle:'
                             . $GLOBALS['cycle']);
                break;
            }
            // compute the previous step
            $query = "select * from " . _LC_CYCLES_TABLE_NAME
                   . " where policy_id = ? and sequence_number = ?";
            $stmt = Bt_doQuery(
                $GLOBALS['db'], 
                $query, 
                array(
                    $GLOBALS['policy'], 
                    $cycleRecordset->sequence_number - 1
                )
            );
            $prevCycleIdWhereClause = '';
            $cptPrevCycleId = 0;
            if ($stmt->rowCount() > 0) {
                while ($cyclePreviousRecordset = $stmt->fetchObject()) {
                    if ($cptPrevCycleId == 0) {
                        $prevCycleIdWhereClause .= "(cycle_id = '" . $cyclePreviousRecordset->cycle_id . "'";
                    } else {
                        $prevCycleIdWhereClause .= " or cycle_id = '" . $cyclePreviousRecordset->cycle_id . "'";
                    }
                    $cptPrevCycleId ++;
                }
                $prevCycleIdWhereClause .= ")";
            } else {
                Bt_exitBatch(12, 'previous cycle not found for policy:'
                             . $GLOBALS['policy'] . ', cycle:'
                             . $GLOBALS['cycle']);
                break;
            }

            // select resources
            if ($cycleRecordset->break_key <> '') {
                $orderBy = ' order by ' . $cycleRecordset->break_key;
            } else {
                $orderBy = ' order by res_id ';
            }
            if ($GLOBALS['stackSizeLimit'] <> '') {
                $limit = ' LIMIT ' . $GLOBALS['stackSizeLimit'];
            }

            $where_clause = " policy_id = '" . $GLOBALS['policy']
                . "' and " . $prevCycleIdWhereClause
                . " and " . $cycleRecordset->where_clause
                . $GLOBALS['creationDateClause']
                . $GLOBALS['whereRegex'];

            $query = $GLOBALS['db']->limit_select(
                0, 
                $GLOBALS['stackSizeLimit'], 
                'res_id', 
                $GLOBALS['view'], 
                $where_clause, 
                $orderBy
            );

            $stmt = Bt_doQuery($GLOBALS['db'], $query);
            $resourcesArray = array();

            if ($stmt->rowCount() > 0) {
                while ($resoucesRecordset = $stmt->fetchObject()) {
                    array_push(
                        $resourcesArray,
                            array('res_id' => $resoucesRecordset->res_id)
                    );
                }
            } else {
                if ($GLOBALS['creationDateClause'] <> '') {
                    $GLOBALS['logger']->write('no resource found for policy:'
                        . $GLOBALS['policy'] . ', cycle:'
                        . $GLOBALS['cycle'] . ' ' 
                        . $GLOBALS['creationDateClause']
                        . ' compute next month if necessary'
                    );
                    // test if we have to change the current date
                    if ($GLOBALS['currentMonthOnly'] == 'false') {
                        $queryTestDate = " select count(res_id) as totalres from " 
                            . $GLOBALS['view'] . " where policy_id = ? and " 
                            . $prevCycleIdWhereClause
                            . " and " . $cycleRecordset->where_clause
                            . $GLOBALS['creationDateClause'];
                        $stmt = Bt_doQuery(
                            $GLOBALS['db'], 
                            $queryTestDate,
                            array(
                                $GLOBALS['policy']
                            )
                        );
                        $resultTotal = $stmt->fetchObject();
                        if ($resultTotal->totalres == 0) {
                            Bt_computeNextMonthCurrentDate();
                            Bt_computeCreationDateClause();
                            Bt_updateCurrentDateToProcess();
                            $where_clause = " policy_id = ? and " 
                                . $prevCycleIdWhereClause
                                . " and " . $cycleRecordset->where_clause
                                . $GLOBALS['creationDateClause']
                                . $GLOBALS['whereRegex'];

                            $query = $GLOBALS['db']->limit_select(
                                0, 
                                $GLOBALS['stackSizeLimit'], 
                                'res_id', 
                                $GLOBALS['view'], 
                                $where_clause, 
                                $orderBy
                            );
                            $stmt = Bt_doQuery(
                                $GLOBALS['db'], 
                                $query,
                                array(
                                    $GLOBALS['policy']
                                )
                            );
                            $resourcesArray = array();
                            if ($stmt->rowCount() > 0) {
                                while ($resoucesRecordset = $stmt->fetchObject()) {
                                    array_push(
                                        $resourcesArray,
                                            array('res_id' => $resoucesRecordset->res_id)
                                        );
                                }
                            } else {
                                Bt_exitBatch(111, 'no resource found for policy:'
                                    . $GLOBALS['policy'] . ', cycle:'
                                    . $GLOBALS['cycle'] . ' ' 
                                    . $GLOBALS['creationDateClause']);
                                break;
                            }
                        }
                    }
                } else {
                    Bt_exitBatch(111, 'no resource found for policy:'
                        . $GLOBALS['policy'] . ', cycle:'
                        . $GLOBALS['cycle'] . ' ' 
                        . $GLOBALS['creationDateClause']);
                    break;
                }
            }
            $state = 'FILL_STACK';
            break;
        /**********************************************************************/
        /*                          FILL_STACK                                */
        /* Fill the stack of candidates from each step of the cycle           */
        /**********************************************************************/
        case 'FILL_STACK' :
            for (
                $cptSteps = 0;
                $cptSteps < count($GLOBALS['steps']);
                $cptSteps++
            ) {
                for ($cptRes = 0;$cptRes < count($resourcesArray);$cptRes++) {
                    $query = "insert into " . _LC_STACK_TABLE_NAME
                           . " (policy_id, cycle_id, cycle_step_id, coll_id, "
                           . "res_id, status, work_batch, regex) "
                           . "values (?, ?, ?, ?, ?, 'I', ?, ?)";
                    $stmt = Bt_doQuery(
                        $GLOBALS['db'], 
                        $query, 
                        array(
                            $GLOBALS['policy'],
                            $GLOBALS['cycle'],
                            $GLOBALS['steps'][$cptSteps]['cycle_step_id'],
                            $GLOBALS['collection'],
                            $resourcesArray[$cptRes]["res_id"],
                            $GLOBALS['wb'],
                            $GLOBALS['regExResId']
                        )
                    );
                    //history
                    if ($GLOBALS['enableHistory']) {
                        $query = "insert into " . HISTORY_TABLE
                               . " (table_name, record_id, event_type, user_id, "
                               . "event_date, info, id_module) values (?, ?, 'ADD', 'LC_BOT', "
                               . $GLOBALS['db']->current_datetime()
                               . ", ?, 'life_cycle')";
                        $stmt = Bt_doQuery(
                            $GLOBALS['db'], 
                            $query,
                            array(
                                $GLOBALS['view'],
                                $resourcesArray[$cptRes]["res_id"],
                                "fill stack, policy:" . $GLOBALS['policy']
                                    . ", cycle:" . $GLOBALS['cycle'] . ", cycle step:"
                                    . $GLOBALS['steps'][$cptSteps]['cycle_step_id']
                                    . "collection:" . $GLOBALS['collection']
                            )
                        );
                    }
                    $GLOBALS['totalProcessedResources']++;
                }
            }
            $state = 'END';
            break;
    }
}
$GLOBALS['logger']->write('End of process fill stack', 'INFO');
include('process_stack.php');
exit($GLOBALS['exitCode']);
