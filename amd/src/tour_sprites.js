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

/**
 * Add animated cat sprites to adaptive tour steps.
 *
 * @module     local_adaptive_course_audit/tour_sprites
 * @copyright  2025 Moodle HQ
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'core/config'], function($, coreConfig) {
    'use strict';

    const STEP_CONTAINER_SELECTOR = '[data-flexitour="container"] .modal-content';
    const CLASS_APPLIED = 'local-aca-tour-animated';
    const SPRITE_CLASS = 'local-aca-tour-sprite';

    /**
     * Determine whether a node is a step container.
     *
     * @param {HTMLElement} node
     * @returns {boolean}
     */
    const isStepContainer = node => {
        return node instanceof HTMLElement && node.matches(STEP_CONTAINER_SELECTOR);
    };

    /**
     * Apply the animated sprite to the tour step.
     *
     * @param {HTMLElement} stepContentEl
     * @param {Object} spriteConfig
     */
    const attachSprite = (stepContentEl, spriteConfig) => {
        const stepContent = $(stepContentEl);
        stepContent.addClass(CLASS_APPLIED);
        stepContent.find(`.${SPRITE_CLASS}`).remove();

        const sprite = document.createElement('div');
        sprite.className = `${SPRITE_CLASS} ${SPRITE_CLASS}--${spriteConfig.variant}`;
        sprite.style.backgroundImage = `url('${spriteConfig.url}')`;
        stepContent.append(sprite);
    };

    /**
     * Observe the document for tour steps and decorate them.
     *
     * @param {Object} spriteConfig
     */
    const watchForSteps = spriteConfig => {
        const processedNodes = new WeakSet();
        const decorate = node => {
            if (processedNodes.has(node)) {
                return;
            }
            processedNodes.add(node);
            attachSprite(node, spriteConfig.nextVariant());
        };

        // Initial scan in case a step is already visible.
        document.querySelectorAll(STEP_CONTAINER_SELECTOR).forEach(node => decorate(node));

        const observer = new MutationObserver(mutations => {
            try {
                mutations.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if (!(node instanceof HTMLElement)) {
                            return;
                        }

                        if (isStepContainer(node)) {
                            decorate(node);
                            return;
                        }

                        const nested = node.querySelectorAll ? node.querySelectorAll(STEP_CONTAINER_SELECTOR) : [];
                        nested.forEach(nestedNode => decorate(nestedNode));
                    });
                });
            } catch (error) {
                window.console.error('Adaptive course audit sprite observer error', error);
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    };

    /**
     * Factory to provide alternating sprite variants.
     *
     * @param {string} talkSprite
     * @param {string} winkSprite
     * @returns {{nextVariant: function(): {variant: string, url: string}}}
     */
    const createSpriteConfig = (talkSprite, winkSprite) => {
        const variants = [
            {variant: 'talk', url: talkSprite},
            {variant: 'wink', url: winkSprite},
        ];
        let index = 0;

        return {
            nextVariant: () => {
                const choice = variants[index % variants.length];
                index++;
                return choice;
            },
        };
    };

    /**
     * Entry point.
     */
    const buildDefaultConfig = () => {
        const wwwroot = coreConfig && coreConfig.wwwroot ? coreConfig.wwwroot :
        (window.M && window.M.cfg && window.M.cfg.wwwroot ? window.M.cfg.wwwroot : '');
        if (!wwwroot) {
            return null;
        }
        return {
            talkSpriteUrl: `${wwwroot}/local/adaptive_course_audit/pix/miau_talk_sprite.png`,
            winkSpriteUrl: `${wwwroot}/local/adaptive_course_audit/pix/miau_wink_sprite.png`,
        };
    };

    const init = config => {
        try {
            const resolvedConfig = (config && config.talkSpriteUrl && config.winkSpriteUrl)
                ? config
                : buildDefaultConfig();
            if (!resolvedConfig || !resolvedConfig.talkSpriteUrl || !resolvedConfig.winkSpriteUrl) {
                window.console.error('Adaptive course audit sprites missing URLs');
                return;
            }

            const spriteConfig = createSpriteConfig(resolvedConfig.talkSpriteUrl, resolvedConfig.winkSpriteUrl);
            watchForSteps(spriteConfig);
        } catch (error) {
            window.console.error('Adaptive course audit sprite initialisation failed', error);
        }
    };

    return {
        init: init,
    };
});

