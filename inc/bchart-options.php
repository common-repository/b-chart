<?php if ( ! defined( 'ABSPATH' )  ) { die; } // Cannot access directly.

//
// Metabox of the PAGE
// Set a unique slug-like ID
//
$prefix = '_bpbcbchart_';

CSF::createMetabox( $prefix, array(
  'title'        => 'B Chart Options',
  'post_type'    => 'bchart',
  'show_restore' => true,
) );

//
// Create a section
//
CSF::createSection( $prefix, array(
  'title'  => 'b-Chart Settings',
  'icon'   => 'fas fa-cofig',
  'fields' => array(
    array(
      'id'    => 'bchart_width',
      'type'  => 'dimensions',
      'title' => 'Chart Width',
      'desc' => 'Set Chart Width, Default Value "100%" ',
      'height' => false,
      'default'  => array(
        'width'  => '100',
        'unit'   => '%',
      ),
      'units'   => array('%')
    ), 
    array(
      'id'    => 'chart_type',
      'type'  => 'button_set',
      'title' => 'Chart Type',
      'desc' => 'Select Chart Type From The List. <cite style="color: #0085ba; font-weight: bold"></cite>',
      'class' => 'chart-readonly',
      'options'     => array(
        'pie' => 'Pie',
        'line' => 'Line',
        'bar' => 'Bar',
        // 'radar' => 'Radar',
        // 'doughnut' => 'Doughnut',
        // 'polarArea' => 'PolarArea'
      ),
      'default'     => 'line',
    ), 
    array(
      'id'    => 'chart_label_name',
      'type'  => 'text',
      'title' => 'Chart Labels',
      'desc' => 'Input List of Label Name by comma(\',\') separate',
      'default'=> 'January, February, March,',
      'dependency' => array(
        array( 'chart_type', 'any', 'line,bar,radar', 'all'),
      ),
    ), 
    // Data Label and Color
    array(
      'id'        => 'chart_opt_special', // chart label and color
      'type'      => 'group',
      'title'     => 'Chart Data',
      'button_title'=> ' Add New Data',
      'subtitle'  => 'Input Same Number Of Data For Every Label/Dataset. Example if Label/Dataset: Red = 2,0,3 Then Rest of The Label / Dataset Will Follow Same Rule.',
      'desc' => 'Create Chart Data',
      'fields'    => array(
        array(
          'id'    => 'data_label',
          'type'  => 'text',
          'title' => 'Label',
          'default'=> 'Red',
        ),
        array(
          'id'    => 'data_list',
          'type'  => 'group',
          'title' => 'Data',
          'subtitle' => 'Only Number Acceptable.',
          'button_title'=> ' Add New Data',
          'fields'    => array(
            array(
              'id'    => 'data',
              'type'  => 'text',
              // 'title' => 'Label',
              'default'=> 5,
            ),
          ),

        ),
        array(
          'id'    => 'data_bg',
          'type'  => 'color',
          'title' => 'Color',
          'default'=> 'rgba(0, 168, 255, 0.3)',

        ),
        array(
          'id'    => 'data_bder_color',
          'type'  => 'color',
          'title' => 'Border Color',
          'default'=> 'transparent',
        ),

      ),
      'dependency' => array(
        array( 'chart_type', 'any', 'pie,polarArea,doughnut', 'all'),
      ),
    ), // End of Data

    // Data Group
    array(
      'id'        => 'data_chart_opt',
      'type'      => 'group',
      'title'     => 'Chart Data',
      'subtitle'  => 'Input Data For Every Label Example if Labels: january,February,March Then Dataset/T-Shirt Data: 10, 0, 3',
      'button_title'=> 'Add New Chart Data',
      'fields'    => array(

        // array(
        //   'id'    => 'area_switcher',
        //   'type'  => 'switcher',
        //   'title' => 'Active Area Option',
        //   'desc' => 'Enable or Disable Area Chart Style',
        //   'default'=> false,
        // ),
        array(
          'id'    => 'chart_label',
          'type'  => 'text',
          'title' => 'Data Label',
          'default'=> 'T-Shirt'
        ), 
        
        // Chart Data
        array(
          'id'        => 'chart_datas',
          'type'      => 'group',
          'title'     => 'Data',
          'subtitle'    => 'Only Number Acceptable.',
          'button_title'=> ' Add New Data',
          'fields'    => array(
            array(
              'id'    => 'data',
              'type'  => 'text',
              'default'=> 2,
            ),
          ),
        ), // End of Data
        array(
          'id'    => 'data_bg',
          'type'  => 'color',
          'title' => 'Data Background',
          'default'=> 'rgba(0, 168, 255, 0.3)',
          'dependency' => array(
            array( 'chart_type', 'any', 'line,bar,radar', 'all'),
          ),

        ),
        array(
          'id'    => 'data_bder_color',
          'type'  => 'color',
          'title' => 'Border Color',
          'default'=> 'rgba(0, 168, 255, 0.9)',
          'dependency' => array(
            array( 'chart_type', 'any', 'line,bar,radar', 'all'),
          ),
        ),
        array(
          'id'    => 'chart_border',
          'type'  => 'spinner',
          'title' => 'Border Width',
          'desc'  => 'Show border Width in px',
          'default'=> 2,
        ), 
        // End of Data Option
      ),
      'dependency' => array(
        array( 'chart_type', 'any', 'line,bar,radar', 'all'),
      ),
    ),


    array(
      'id'    => 'chart_tension',
      'type'  => 'spinner',
      'title' => 'Tension',
      'subtitle' => ' Use Number (0.1 - 0.9 or Above )',
      'desc' => 'Choose Tension limit for chart border curve <cite style="color:red">( Tension will not work properly if animation loop enabled )</cite>',
      'default'=> 0,
      'dependency' => array(
        array( 'chart_type', 'any', 'line,radar', 'all'),
      ),
    ),
    // Chart Title and Config
    array(
      'id'    => 'chart_point_style',
      'type'  => 'select',
      'title' => 'Chart Pointer Style',
      'desc' => 'Choose Pointer Style From The List, Leave Blank for Default Value',
      'options'  => array(
        'circle' => 'Circle',
        'cross' => 'Cross',
        'crossRot' => 'CrossRot',
        'dash' => 'Dash',
        'line' => 'Line',
        'rect' => 'Rect',
        'rectRounded' => 'RectRounded',
        'rectRot' => 'RectRot',
        'star' => 'star',
        'triangle' => 'Triangle'
 
      ),
      'default'     => 'line',
      'dependency' => array(
        array( 'chart_type', 'any', 'line,bar,radar', 'all'),
      ),
    ),
    array(
      'id'    => 'pointer_size',
      'type'  => 'spinner',
      'title' => 'Pointer Size',
      'desc' => 'Input Pointer Size',
      'default'=> 2,
      'dependency' => array(
        array( 'chart_type', 'any', 'line,bar,radar', 'all'),
      ),
    ),
    array(
      'id'    => 'pointer_hover_radius',
      'type'  => 'spinner',
      'title' => 'Pointer Hover Size',
      'desc' => 'Input Pointer  Size On Hover',
      'default'=> 4,
      'dependency' => array(
        array( 'chart_type', 'any', 'line,bar,radar', 'all'),
      ),
    ),
    //End Chart Pointer Style and Config
    array(
      'id'    => 'chart_grid_color',
      'type'  => 'color',
      'title' => 'Chart Grid Color',
      'desc' => 'Choose Grid Color, Leave Blank for Default Value Or Choose Transparent Color to hide Grid',
      'default'=> '#ddd',
    ),
    array(
      'id'    => 'chart_label_color',
      'type'  => 'color',
      'title' => 'Chart Grid Label Color',
      'desc' => 'Choose Grid Label Color, Leave Blank for Default Value.',
      'default'=> '#555',
    ),
    // End oF Chart Grid and Label
    array(
      'id'    => 'legend_position',
      'type'  => 'button_set',
      'title' => 'Legend Position',
      'desc' => 'Choose Legend Position From The List',
      'options'     => array(
        'top' => 'Top',
        'left' => 'Left',
        'bottom' => 'Bottom',
        'right' => 'Right',
      ),
      'default'     => 'top'
    ), 
    array(
      'id'    => 'legend_color',
      'type'  => 'color',
      'title' => 'legend Color',
      'subtitle' => 'Set legend Text / Label Color',
      'desc' => 'Choose legend Color',
      'default'=> '#222'
    ), 
    array(
      'id'    => 'legend_pstyle',
      'type'  => 'switcher',
      'title' => 'legend Point Style',
      'desc' => 'Enable or Disable legend Point Style',
      'default'=> true,
    ), 
    // End Legend and Config

    //Title and  Subtitle
    array(
      'type'    => 'notice',
      'style'   => 'success',
      'content' => 'Chart Title and Subtitle Options : ',
      'class' => 'option_title',
    ),
    array(
      'id'    => 'chart_title_opt',
      'type'  => 'switcher',
      'title' => 'Title Content',
      'desc'  => 'Show or Hide Chart Title',
      'text_on'  => 'Show',
      'text_off' => 'Hide',
      'text_width' => 70,
      'default'=> true,
    ),   
    array(
      'id'    => 'chart_title',
      'type'  => 'text',
      'title' => 'Title',
      'desc' => 'Input Chart Name/Title Here',
      'default'=> 'Selling Report of Company',
      'dependency' => array( 'chart_title_opt', '==', '1' )
    ), 
    array(
      'id'    => 'title_color',
      'type'  => 'color',
      'title' => 'Title Color',
      'desc' => 'Choose Title Color',
      'default'=> '#000',
      'dependency' => array( 'chart_title_opt', '==', '1' )
    ), 
    array(
      'id'    => 'title_font_size',
      'type'  => 'number',
      'title' => 'Title Font-Size',
      'desc' => 'Input Title Font-Size, No Need to use (px or em) Just Use Number',
      'default'=> '16',
      'dependency' => array( 'chart_title_opt', '==', '1' )
    ), 
    // Chart Sub-Title and Config
    array(
      'id'    => 'chart_subtitle_opt',
      'type'  => 'switcher',
      'title' => 'SubTitle Content',
      'desc'  => 'Show or Hide Chart Sub-Title',
      'text_on'  => 'Show',
      'text_off' => 'Hide',
      'text_width' => 70,
      'default'=> true,
    ),  
    array(
      'id'    => 'chart_subtitle',
      'type'  => 'text',
      'title' => 'Subtitle',
      'desc' => 'Input Chart Subtitle Here',
      'default'=> 'Subtitle',
      'dependency' => array( 'chart_subtitle_opt', '==', '1' )
    ), 
    array(
      'id'    => 'subtitle_color',
      'type'  => 'color',
      'title' => 'Subtitle Color',
      'desc' => 'Choose Subtitle Color',
      'default'=> '#333',
      'dependency' => array( 'chart_subtitle_opt', '==', '1' )
    ), 
    array(
      'id'    => 'subtitle_font_size',
      'type'  => 'number',
      'title' => 'Subtitle Font-Size',
      'desc' => 'Input Subtitle Font-Size, No Need to use (px or em) Just Use Number',
      'default'=> '14',
      'dependency' => array( 'chart_subtitle_opt', '==', '1' )
    ), 

  // End Title and Sub-Title area

  // ANIMATION OPTIONS
  array(
    'type'    => 'notice',
    'style'   => 'success',
    'content' => 'Chart Animation Options : ',
    'class' => 'option_title',
    'dependency' => array(
      array( 'chart_type', 'any', 'line,radar', 'all'),
    )
  ),
  array(
    'id'    => 'animation_loop',
    'type'  => 'switcher',
    'title' => 'Animation Loop',
    'desc' => 'Enable or Disable Animation Loop',
    'default'=> false,
    'dependency' => array(
      array( 'chart_type', 'any', 'line,radar', 'all'),
    )
  ), 
  array(
    'id'    => 'animation_type',
    'type'  => 'select',
    'title' => 'Animation Type',
    'subtitle' => 'Chart Border Animation',
    'desc' => 'Choose Animation Type From The List',
    'options'     => array(
    'linear' => 'Linear',
    'easeInQuad' => 'EaseInQuad',
    'easeOutQuad' => 'EaseOutQuad',
    'easeInOutQuad' => 'EaseInOutQuad',
    'easeInCubic' => 'EaseInCubic',
    ),
    'default'     => 'Linear',
    'dependency' => array(
      array( 'chart_type', 'any', 'line,radar', 'all'),
      array( 'animation_loop', '==', '1'),
    )
  ), 
  array(
    'id'    => 'animation_duration',
    'type'  => 'number',
    'title' => 'Animation Duration',
    'desc' => 'Input Animation Duration Here',
    'default'=> 1000,
    'dependency' => array(
      array( 'chart_type', 'any', 'line,radar', 'all'),
      array( 'animation_loop', '==', '1'),
    )
  ), 
  array(
    'id'    => 'animation_tension',
    'type'  => 'number',
    'title' => 'Animation Tension',
    'desc' => 'Input Animation Tension Limit Here Use Number (0.1 - 0.9 or Above )',
    'default'=> 0,
    'dependency' => array(
      array( 'chart_type', 'any', 'line,radar', 'all'),
      array( 'animation_loop', '==', '1')
    )
  ), 



  )

) );





