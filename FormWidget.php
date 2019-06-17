<?php

// Register and load the widget
function widgetInit()
{
    register_widget('FormWidget');
}
add_action('widgets_init', 'widgetInit');

class FormWidget extends WP_Widget
{
    public $widget_ID;
    public $widget_name;
    public $widget_description;
    public $widget_options = array();
    public $control_options = array();

    public function __construct()
    {
        $this->widget_ID = 'widget_teste';
        $this->widget_name = 'A TEST Widget';
        $this->widget_description = 'The first widget I made';
        $this->widget_options = array(
            'classname'                     => $this->widget_ID,
            'description'                   => $this->widget_description,
            'customize_selective_refresh'   => true
        );

        $this->control_options = array(
            'width' => 400,
            'height' => 400
        );

        parent::__construct($this->widget_ID, $this->widget_name, $this->widget_options, $this->control_options);
    }

    // Widget Front End
    public function widget($args, $instance)
    {
        echo $args['before_widget'];

        if (is_404()){
            if (!empty(apply_filters('widget_title', $instance['title']))) {
                echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
            }
        }
        ?>
        <script type="text/javascript">
            //** Gets values from local storage if they exist */
            window.onload = function() {
                //get the saved value function - return the value of "v" from localStorage. 
                function getSavedValue(v) {
                    if (!localStorage.getItem(v)) {
                        return ""; // defualt value. 
                    }
                    return localStorage.getItem(v);
                }

                document.getElementById("colorchoice").value = getSavedValue('color');
                document.getElementById("livescount").value = getSavedValue('lives');
            }

            function reload() {
                var color = document.getElementById("colorchoice").value;
                var livesNumber = document.getElementById("livescount").value;
                localStorage.setItem('color', color); // Every time user is writing something, the localStorage's value will override . 
                localStorage.setItem('lives', livesNumber);
                location.reload();
            }
        </script>

        <?php
        if (is_404()){
        ?>
        
        <script>
            var gameContainer = '<div id="game-container">' +
                    '<h4>Customize</h4>' +
                        '<form action="#" id="color-form" style="margin:20px">' +
                            '<ul>' +
                                '<li>' +
                                    '<label for="colorchoice">Color Choices</label>' +
                                    '<select name="colorchoice" id="colorchoice">' +
                                        '<option value="yellow">Yellow</option>' +
                                        '<option value="red">Red</option>' +
                                        '<option value="blue">Blue</option>' +
                                        '<option value="green">Green</option>' +
                                    '</select>' +
                                '</li>' +
                                '<li>' +
                                    '<label for="livescount">Number of lives</label>' +
                                    '<input type="number" name="livescount" id="livescount" value="" min="1" />' +
                                '</li>' +
                                '<li>' +
                                    '<a id="game-link" href="#" onclick="reload()"> Customize the game </a>' +
                                '</li>' +
                            '</ul>' +
                        '</form>' +
                    '<div id="game-area" class="game-area" style="width:480px; height:320px; margin: 20px auto;">' +
                    '</div>' +
                '</div>';

            var divElement = document.createElement('div');

            divElement.innerHTML = gameContainer;

            var footer = document.getElementById('primary-sidebar');
            
            footer.appendChild(divElement);

            console.log(document.getElementById('game-area'));
            console.log('2')
        </script>
        <?php
        }
        echo $args['after_widget'];
    }

    // Widget Back End
    public function form($instance)
    {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('New Title');
        $formColor = !empty($instance['formColor']) ? $instance['formColor'] : esc_html__('');
        $formColorCode = !empty($instance['formColorCode']) ? $instance['formColorCode'] : esc_html__('');


        $titleID = esc_attr($this->get_field_id('title'));
        $formColorID = esc_attr($this->get_field_id('formColor'));
        $formColorCodeID = esc_attr($this->get_field_id('formColorCode'));

        ?>
        <!-- TITLE -->
        <p>
            <label for="<?php echo $titleID; ?>">Title:</label>
            <input type="text" class="widefat" id="<?php echo $titleID; ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($title) ?>" />
        </p>
        <!-- COLOR FORM -->
        <!-- <p>
                    COLOR NAME 
                    <div style="padding-bottom: 10px;"> 
                        <label for="<?php echo $formColorID; ?>">Color:</label>
                        <input 
                        type="text" 
                        class="widefat" 
                        id="<?php echo $formColorID; ?>" 
                        name="<?php echo esc_attr($this->get_field_name('formColor')); ?>" 
                        value="<?php echo esc_attr($formColor) ?>" />
                    </div>
                </p>
                <p>
                    COLOR CODE 
                    <div style="padding-bottom: 10px;"> 
                        <label for="<?php echo $formColorCodeID; ?>">Color Code:</label>
                        <input 
                        type="text" 
                        class="widefat" 
                        id="<?php echo $formColorCodeID; ?>" 
                        name="<?php echo esc_attr($this->get_field_name('formColorCode')); ?>" 
                        value="<?php echo esc_attr($formColorCode) ?>" />
                    </div>
                    <a href="#" onClick="onClick()" style="color:black; padding: 10px; display:block;">Add Color</a>
                </p>
                    COLOR TABLE 
                <p>
                    <table class="widefat" id="color-options" style="Border: 1px solid black">
                        <tr>
                            <th style="font-weight:bold;">Color Name</th>
                            <th style="font-weight:bold;">Color Code</th>
                            <th style="font-weight:bold;">Color</th>
                        </tr>
                        <tr>
                            <td>Blue</td>
                            <td>0095DD</td>
                            <td style="background-color: #0095DD"></td>
                        </tr>
                        <tr>
                            <td>Red</td>
                            <td>FF0000</td>
                            <td style="background-color: #FF0000"></td>
                        </tr>
                        <tr>
                            <td>Yellow</td>
                            <td>FFFF00</td>
                            <td style="background-color: #FFFF00"></td>
                        </tr>
                        <tr>
                            <td>Green</td>
                            <td>01DF01</td>
                            <td style="background-color: #01DF01"></td>
                        </tr>
                    </table>
                </p> -->
        <script>
            var newColor;
            var newColorCode;
            var submitCount = 1;

            var colorOptionsTable = document.getElementById("color-options");

            var colorTable;

            function onClick() {
                submitCount++;
                var submitNum = "submit_" + submitCount;

                if (submitCount < 3) {
                    colorTable = '{"submit_1":[{ "colorName": "Blue", "colorCode": "0095DD" }]';
                } else {
                    colorTable = colorTable;
                }

                console.log(colorTable);

                newColor = document.getElementById("<?php echo $formColorID; ?>").value;
                newColorCode = document.getElementById("<?php echo $formColorCodeID; ?>").value;

                console.log(newColorCode);

                if (newColorCode.length == 7) {
                    var newObj = '{"' + submitNum + '":[{"colorName": "' + newColor + '", "colorCode": "' + newColorCode + '"}]}';
                } else {
                    alert('Needs a correct color code');
                    return null;
                }
                console.log(newObj);

                colorTable += newObj + "}";

                console.log(colorTable);

                var trTableElement = document.createElement('tr');

                var tableElement = "<td>" + newColor + "</td> <td>" + newColorCode + "</td> <td style=\"background-color: #" + newColorCode + " \"></td>";

                trTableElement.innerHTML = tableElement;
                colorOptionsTable.appendChild(trTableElement);

                console.log(colorOptionsTable);

                return colorTable;
            }
        </script>

    <?php
    }

    // Widget Update
    public function update($new_instance, $old_instance)
    {
        $instance = array();

        $instance['title'] = sanitize_text_field($new_instance['title']);
        // $instance['formColor'] = sanitize_text_field( $new_instance['formColor']);
        // $instance['formColorCode'] = sanitize_text_field( $new_instance['formColorCode']);

        return $instance;
    }
    }
