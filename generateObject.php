
<!--THE FORM-->
<style>
#container {
    width:960px;
    margin:auto;
}
#form {
    width:300px;
    border:solid thin;
    padding:10px;
    float:left;
    height:400px;
    overflow-y:scroll;
}
#code {
    
    height:400px;
    border:solid thin;
    overflow:hidden;
    padding:10px;
    overflow-y:scroll;
}
#error {
    color:red;
    font-weight:bold;
    text-decoration:blink;
}
</style>

<?php

$errors = array();
$variable_count = 0;

// check if form has been submitted
// validate form
// set errors
//kill all humans
if($_POST) {
    
    // check if at least one of the variables were completed
    $variable_present = array();
    foreach($_POST as $var_name => $posted_item) {
        
        // if the posted item is a variable name, at least one must be completed
        if(strstr($var_name, "variable")) { // only checking posted items with "variable" in name
            if(!blank_field($posted_item)) {
                $variable_present []= TRUE;
                $_SESSION[$var_name] = $posted_item;
            }
            $variable_count ++;
        }
        
        // testing the number of variable posted
        if($var_name == "num_of_vars") {
            if(blank_field($posted_item) && $_POST['submit'] == "") {
                $num_of_vars = 3;
            }
            else {
                $num_of_vars = $variable_count + $posted_item;
                //$_SESSION['num_of_vars'] = $num_of_vars;
            }
        }
        
        if($var_name == "class_name") {
            if(blank_field($posted_item)) {
                $errors = print_code_error($errors, "Please provide an Object Name");
            }
            else {
                $_SESSION['class_name'] = $posted_item;
            }
        }
    }

    // a variable is found
    if(in_array(TRUE, $variable_present)) {
        // continue
    }
    else { // no variables found
        $errors = print_code_error($errors, "Please provide at least one variable");
    }
}
else {
    // continue
    // default values
    $num_of_vars = 3;
}
?>

<div id="container">
<div id="form">
<form name="add_more_variables" method="POST" action="<?php print $_SERVER['PHP_SELF']; ?>">
    
Programming Language<br>
<select name = "language">
    <!--option value="c#">C#</option-->
    <option value="vb">VB</option>
    <option value="php">PHP</option>
</select>
<br><br>

Encaptulation for all variables.<br>
<select name = "encapsulation">
    <option value="public">public</option>
    <option value="private">private</option>
    <option value="protected">protected</option>
</select>
    
<?php
newline(2);
print "Object Name:"; newline();
print '<input type="text" name="class_name" value="'.get_saved_value("class_name").'" />';

newline(2);

// print variable names input boxes
for($i=1; $i<=$num_of_vars; $i++) { // how many depends on submitted num_of_vars (defaults to 3)
    if($i == 1) {
        print "Variable Names:";
        newline();
    }
    print '<input type="text" name="variable'.$i.'" value="'.get_saved_value("variable".$i).'" />'; newline();
}

newline();
?>

Need more? <input style="width:30px;" type="text" name="num_of_vars" />
<input type="submit" value="Add" name="submit" /><br>

<input type="submit" name="submit" value="Generate Code" />
<input type="reset" value="Clear" />
</form>

<?php
// exit if errors exist
if(!empty($errors)) {
    exit(print_errors($errors));    
}
?>
</div>


<div id="code">

<?php
$language = get_posted_value('language');
$class = get_posted_value('class_name');
$encapulation = get_posted_value('encapsulation');

$variableName = "variableName"; // will be found and replaced later

switch($language) {
    case "php" :
        $tagOpen = "&lt;?php";
        $classOpen = "{";
        $classClose = "}";
        $tagClose = "?&gt;";
        $comments = "//";
        $constructorOpen = "public function __construct()";
        $constructorClose = "}";
        $declareVariable = " $" . $variableName . ";";
        
        $transformerOpen = "public function set_" . $variableName . '($value){';
        $transformerSet = '$this->' . $variableName . ' = $value;';
        $transformerClose = "}";
        
        $accessorOpen = "public function get_" . $variableName . '(){';
        $accessorGet = 'return $this->' .  $variableName . ";";
        $accessorClose = "}";
    break;
    
    case "vb" :
        // use Object for all variable types - so technically it will work but they may be changed later
        $tagOpen = "";
        $tagClose = "";
        $classOpen = "";
        $classClose = "End Class";
        $comments = "'";
        $constructorOpen = "Public Sub $class()";
        $constructorClose = "End Sub";
        $declareVariable = " " . $variableName . " AS Object";
        
        $transformerOpen = "Public Sub set" . $variableName. "(ByVal value As Object)";
        $transformerSet = 'Me.' . $variableName . ' = value';
        $transformerClose = "End Sub";
        
        $accessorOpen = "Public Function get" . $variableName . "() As Object";
        $accessorGet = "Return Me." . $variableName;
        $accessorClose = "End Function";
        
        
    break;

    default : "";
    break;
}

$author = $comments . " @Author: Andrew Osiname<br>";
$author .= $comments . " @Date: " . date('d/M/Y H:i:s');
// add more comments here

print $tagOpen; newline();
print $author; newline(2);
print "public Class " . $class . $classOpen; newline();

// get all posted values
foreach($_POST as $key => $value) {
    if(strstr($key, "variable") && $value != "") {
        $variable_names[]= $value;
    }
}

// print the variables with their encapsulation
if(isset($variable_names)){
    foreach($variable_names as $variable) {
        tab(); print $encapulation . str_replace($variableName, $variable, $declareVariable); newline();
    }
}

// CONSTRUCTOR(S)
newline();
if(isset($variable_names)){
    tab(); print $comments . " CONSTRUCTOR(S)"; newline();
    tab(); print $constructorOpen . $classOpen; newline();
    tab(); print $constructorClose;
}
else {
    print "Please complete the form";
}

// TRANSFORMERS
newline(2);
if(isset($variable_names)){
    tab(); print $comments . " TRANSFORMERS"; newline();
    foreach($variable_names as $variable) {
        tab(); print str_replace($variableName, ucfirst($variable), $transformerOpen);
        newline();
        tab(2); print str_replace($variableName, $variable, $transformerSet);
        newline();
        tab(); print $transformerClose;
        newline(2);
    }
}

// ACCESSORS
if(isset($variable_names)){
    tab(); print $comments . " ACCESSORS"; newline();
    foreach($variable_names as $variable) {
        tab(); print str_replace($variableName, ucfirst($variable), $accessorOpen);
        newline();
        tab(2); print str_replace($variableName, $variable, $accessorGet);
        newline();
        tab(); print $accessorClose;
        newline(2);
    }
}

print $classClose;
newline();
print $tagClose;

function print_errors($errors) {
    foreach($errors as $error) {
        print '<div id="error">' . $error . '</div>';
    }
}
function newline($how_many = 1) {
    if($how_many < 0) {
        $how_many = $how_many * -1;
    }
    for($i=1; $i<=$how_many; $i++) {
        print "<br>";    
    }
    
}

/**
 * If this is populated, code is not printed
 * The errors will be displayed instead.
 */
function print_code_error($error_array, $error_msg) {
    $error_array[sizeof($error_array)] = $error_msg;
    return $error_array;
}

function tab($how_many = 1) {
    for($i=1; $i<=$how_many; $i++) {    
        print "&nbsp;&nbsp;&nbsp;&nbsp;";
    }
}

function get_saved_value($variable_name) {
    if(isset($_SESSION[$variable_name])) {
        return $_SESSION[$variable_name];
    }
}

function get_posted_value($value_name) {
    if(isset($_POST[$value_name])) {
        return $_POST[$value_name];
    }
}

function blank_field($field) {
    if($field == "") {
        return TRUE;
    }
    else {
        return FALSE;
    }
    // should never get here
    return TRUE;
}
?>
</div><!--close #code-->
</div><!-- close #container-->
