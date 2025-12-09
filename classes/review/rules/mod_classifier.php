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
 * Minimal module classifier for adaptive audit rules.
 *
 * @package     local_adaptive_course_audit
 */
class mod_classifier {
    /** @var string Knowledge building modules constant. */
    public const MOD_WISSENSAUFBAU = 'wissensaufbau';

    /** @var array Module types classified as knowledge building. */
    private static $wissensaufbau_modules = [
        'book',
        'page',
        'resource',
        'url',
        'folder',
        'file',
        'label',
        'glossary',
        'wiki',
        'forum',
        'chat',
        'choice',
        'feedback',
        'survey',
        'lesson',
        'scorm',
        'hvp',
        'h5pactivity',
        'lti',
        'externalcontent',
    ];

    /**
     * Check if a module type belongs to the knowledge building category.
     *
     * @param string $modtype Module type.
     * @param string $category Category to check against.
     * @return bool
     */
    public static function is_module_in_category(string $modtype, string $category): bool {
        $modtype = strtolower($modtype);

        if ($category !== self::MOD_WISSENSAUFBAU) {
            return false;
        }

        return in_array($modtype, self::$wissensaufbau_modules, true);
    }
}

