/**
 * Block JavaScript.
 *
 * @package    Wplocalplus_Lite
 * @subpackage Wplocalplus_Lite/assets
 * @author     wpeka <https://club.wpeka.com>
 */

(function( $ ) {
    const {registerBlockType} = wp.blocks; // Blocks API.
    const {createElement} = wp.element; // React.createElement.
    const {__} = wp.i18n; // Translation functions.
    const {InspectorControls} = wp.editor; //Block inspector wrapper.
    const {TextControl,SelectControl} = wp.components; //Block inspector wrapper.

    registerBlockType( 'wplocalplus-lite/block', {
        title: __( 'WPLocalPlus Business List' ),
        category:  __( 'common' ),
        keywords: [
            __('wplocalplus'),
            __('list'),
            __('business list'),
            __('localplus'),
        ],
        attributes:  {
            list : {
                default: 'wplocal_places',
            },
            type : {
                default: 'hotels',
            },
            location : {
                default: 'cambridgema',
            },
            limit: {
                default: 5,
            },
        },
        edit(props){
            const attributes =  props.attributes;
            const setAttributes =  props.setAttributes;

            function changeList(list){
                setAttributes({list});
            }

            function changeLimit(limit){
                setAttributes({limit});
            }

            function changeType(type){
                setAttributes({type});
            }

            function changeLocation(location){
                setAttributes({location});
            }

            return createElement('div', {}, [
                // Preview will go here.
                createElement( 'div', {}, '[wplocalplus list="'+attributes.list+'" type="'+attributes.type+'" location="'+attributes.location+'" limit="'+attributes.limit+'"]' ),
                // Block inspector.
                createElement( InspectorControls, {},
                    [
                        createElement(SelectControl, {
                            value: attributes.list,
                            label: __( 'List' ),
                            onChange: changeList,
                            type: 'string',
                            options: [
                                {value: 'wplocal_places', label: 'Places'},
                                {value: 'wplocal_reviews', label: 'Reviews'},
                            ]
                        }),
                        createElement(SelectControl, {
                            multiple: 'multiple',
                            value: attributes.type,
                            label: __( 'Place Type' ),
                            onChange: changeType,
                            type: 'string',
                            options: place_types,
                        }),
                        createElement(SelectControl, {
                            multiple: 'multiple',
                            value: attributes.location,
                            label: __( 'Location' ),
                            onChange: changeLocation,
                            type: 'string',
                            options: locations,
                        }),
                        createElement(TextControl, {
                            value: attributes.limit,
                            label: __( 'Limit per Page' ),
                            onChange: changeLimit,
                            type: 'number',
                            min: 1,
                            step: 1
                        }),
                    ]
                )
            ] )
        },
        save(){
            return null; // Save has to exist. This all we need.
        }
    });
})( jQuery );
