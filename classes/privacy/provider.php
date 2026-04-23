<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

declare(strict_types=1);

namespace local_adaptive_course_audit\privacy;

use context;
use context_course;
use core_privacy\local\metadata\collection;
use core_privacy\local\metadata\provider as metadata_provider;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\approved_userlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\plugin\provider as request_provider;
use core_privacy\local\request\transform;
use core_privacy\local\request\userlist;
use core_privacy\local\request\writer;

/**
 * Privacy provider for local_adaptive_course_audit.
 *
 * @package     local_adaptive_course_audit
 * @copyright   2025 Bastian Schmidt-Kuhl <bastian.schmidt-kuhl@ruhr-uni-bochum.de>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class provider implements
    \core_privacy\local\request\userlist_provider,
    metadata_provider,
    request_provider {
    /** @var string Stores the latest review start per user/course. */
    private const REVIEW_TABLE = 'local_adaptive_course_review';

    /**
     * Describe stored personal data.
     *
     * @param collection $collection
     * @return collection
     */
    public static function get_metadata(collection $collection): collection {
        $collection->add_database_table(self::REVIEW_TABLE, [
            'courseid' => 'privacy:metadata:local_adaptive_course_review:courseid',
            'userid' => 'privacy:metadata:local_adaptive_course_review:userid',
            'sectionid' => 'privacy:metadata:local_adaptive_course_review:sectionid',
            'reviewurl' => 'privacy:metadata:local_adaptive_course_review:reviewurl',
            'timecreated' => 'privacy:metadata:local_adaptive_course_review:timecreated',
            'timemodified' => 'privacy:metadata:local_adaptive_course_review:timemodified',
        ], 'privacy:metadata:local_adaptive_course_review');

        return $collection;
    }

    /**
     * Get contexts containing user data.
     *
     * @param int $userid
     * @return contextlist
     */
    public static function get_contexts_for_userid(int $userid): contextlist {
        $contextlist = new contextlist();

        $sql = "SELECT ctx.id
                  FROM {" . self::REVIEW_TABLE . "} review
                  JOIN {context} ctx
                    ON ctx.instanceid = review.courseid
                   AND ctx.contextlevel = :contextlevel
                 WHERE review.userid = :userid";

        $params = [
            'contextlevel' => CONTEXT_COURSE,
            'userid' => $userid,
        ];

        $contextlist->add_from_sql($sql, $params);
        return $contextlist;
    }

    /**
     * Export user data in approved contexts.
     *
     * @param approved_contextlist $contextlist
     */
    public static function export_user_data(approved_contextlist $contextlist): void {
        global $DB;

        if (empty($contextlist->get_contextids())) {
            return;
        }

        $userid = (int)$contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $context) {
            if (!$context instanceof context_course) {
                continue;
            }

            $records = $DB->get_records(self::REVIEW_TABLE, [
                'courseid' => (int)$context->instanceid,
                'userid' => $userid,
            ]);

            if (empty($records)) {
                continue;
            }

            $exportrecords = [];
            foreach ($records as $record) {
                $exportrecords[] = (object)[
                    'sectionid' => (int)$record->sectionid,
                    'reviewurl' => (string)$record->reviewurl,
                    'timecreated' => transform::datetime((int)$record->timecreated),
                    'timemodified' => transform::datetime((int)$record->timemodified),
                ];
            }

            writer::with_context($context)->export_data(
                [get_string('privacy:metadata:exportpath', 'local_adaptive_course_audit')],
                (object)['reviewstarts' => $exportrecords]
            );
        }
    }

    /**
     * Delete all user data in a context.
     *
     * @param context $context
     */
    public static function delete_data_for_all_users_in_context(context $context): void {
        global $DB;

        if (!$context instanceof context_course) {
            return;
        }

        $DB->delete_records(self::REVIEW_TABLE, ['courseid' => (int)$context->instanceid]);
    }

    /**
     * Delete all user data for approved contexts.
     *
     * @param approved_contextlist $contextlist
     */
    public static function delete_data_for_user(approved_contextlist $contextlist): void {
        global $DB;

        $userid = (int)$contextlist->get_user()->id;
        foreach ($contextlist->get_contexts() as $context) {
            if (!$context instanceof context_course) {
                continue;
            }

            $DB->delete_records(self::REVIEW_TABLE, [
                'courseid' => (int)$context->instanceid,
                'userid' => $userid,
            ]);
        }
    }

    /**
     * Get users in a context who have stored data.
     *
     * @param userlist $userlist
     */
    public static function get_users_in_context(userlist $userlist): void {
        $context = $userlist->get_context();
        if (!$context instanceof context_course) {
            return;
        }

        $sql = "SELECT userid
                  FROM {" . self::REVIEW_TABLE . "}
                 WHERE courseid = :courseid";
        $userlist->add_from_sql('userid', $sql, ['courseid' => (int)$context->instanceid]);
    }

    /**
     * Delete user data for multiple users in one context.
     *
     * @param approved_userlist $userlist
     */
    public static function delete_data_for_users(approved_userlist $userlist): void {
        global $DB;

        $context = $userlist->get_context();
        if (!$context instanceof context_course) {
            return;
        }

        $userids = $userlist->get_userids();
        if (empty($userids)) {
            return;
        }

        [$insql, $params] = $DB->get_in_or_equal($userids, SQL_PARAMS_NAMED, 'userid');
        $params['courseid'] = (int)$context->instanceid;
        $DB->delete_records_select(self::REVIEW_TABLE, "courseid = :courseid AND userid $insql", $params);
    }
}
