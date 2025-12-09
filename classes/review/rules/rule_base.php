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

namespace local_adaptive_course_audit\review\rules;

defined('MOODLE_INTERNAL') || die();

/**
 * Minimal base class for adaptive course audit rules.
 *
 * Only contains the parts required by loop-based hints.
 *
 * @package     local_adaptive_course_audit
 */
abstract class rule_base implements rule_interface {

    /** @var string Rule key. */
    protected $key;

    /** @var string Target type. */
    protected $target;

    /** @var string Rule name. */
    protected $name;

    /** @var string Rule description. */
    protected $description;

    /** @var string Rule category ('hint' or 'action'). */
    protected $category;

    /**
     * Constructor.
     *
     * @param string $key Rule key.
     * @param string $target Target type.
     * @param string $name Rule name.
     * @param string $description Rule description.
     * @param string $category Rule category.
     */
    public function __construct(string $key, string $target, string $name, string $description, string $category) {
        $this->key = $key;
        $this->target = $target;
        $this->name = $name;
        $this->description = $description;
        $this->category = $category;
    }

    /**
     * Get the rule key.
     *
     * @return string
     */
    public function get_key(): string {
        return $this->key;
    }

    /**
     * Get the target type.
     *
     * @return string
     */
    public function get_target(): string {
        return $this->target;
    }

    /**
     * Get the rule name.
     *
     * @return string
     */
    public function get_name(): string {
        return $this->name;
    }

    /**
     * Get the rule description.
     *
     * @return string
     */
    public function get_description(): string {
        return $this->description;
    }

    /**
     * Get the rule category.
     *
     * @return string
     */
    public function get_category(): string {
        return $this->category;
    }

    /**
     * Build a simple result object.
     *
     * @param bool $status
     * @param array $messages
     * @param int|null $targetid
     * @param int|null $courseid
     * @param array $actions Optional associative arrays with 'label', 'url', and 'type'.
     * @return object
     */
    protected function create_result(
        bool $status,
        array $messages = [],
        int $targetid,
        int $courseid,
        array $actions = []
    ): object {
        return (object)[
            'status' => $status,
            'messages' => $messages,
            'rule_name' => $this->name,
            'rule_category' => $this->category,
            'rule_key' => $this->key,
            'rule_target' => $this->target,
            'rule_target_id' => $targetid,
            'course_id' => $courseid,
            'actions' => $actions,
        ];
    }
}

