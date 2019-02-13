<?php
use aki\vue\Vue;
?>
<?php Vue::begin([
    'id' => "vue-app",
    'data' => [
        'userModel' => "11",
        'message' => "hello",
        'seen' => false,
        'todos' => [
            ['text' => "aa"],
            ['text' => "akbar"]
        ]
    ],
    'methods' => [
        'reverseMessage' => new yii\web\JsExpression("function(){"
                . "this.message =1; "
                . "}"),
    ]
]); ?>
    
    <p>{{ message }}</p>
    <button v-on:click="reverseMessage">Reverse Message</button>
    
    <p v-if="seen">Now you see me</p>
    
    
    <ol>
        <li v-for="todo in todos">
          {{ todo.text }}
        </li>
    </ol>
    
    <p>{{ message }}</p>
    <input v-model="message">
  
  
<?php Vue::end(); ?>