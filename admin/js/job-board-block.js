const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const { PanelBody, RangeControl } = wp.components;
const { __ } = wp.i18n;
const { ServerSideRender } = wp.editor;

registerBlockType('job-board/job-listings', {
    title: __('Job Listings', 'job-board'),
    icon: 'businessperson',
    category: 'widgets',
    attributes: {
        posts_per_page: {
            type: 'number',
            default: 10,
        },
    },
    edit: function(props) {
        const { attributes, setAttributes } = props;

        return [
            <InspectorControls key="inspector">
                <PanelBody title={__('Job Listings Settings', 'job-board')}>
                    <RangeControl
                        label={__('Number of listings to show', 'job-board')}
                        value={attributes.posts_per_page}
                        onChange={(value) => setAttributes({ posts_per_page: value })}
                        min={1}
                        max={50}
                    />
                </PanelBody>
            </InspectorControls>,
            <div key="job-listings-block">
                <ServerSideRender
                    block="job-board/job-listings"
                    attributes={attributes}
                />
            </div>
        ];
    },
    save: function() {
        return null; // Render via PHP
    },
});