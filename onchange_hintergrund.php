<!DOCTYPE html>
<html>
<body>


<p>Select a new car from the list.</p>

<input  type='text' value='' name='$name' onchange="myFunction()">



<p>When you select a new car, a function is triggered which outputs the value of the selected car.</p>

<p id="demo"></p>
<tr>
<td>
test
</td>
<td width='20%'><input type='text' id="mySelect" value='hallo
' name='$name' onchange='myFunction()' size='10'></td>
</tr>

<script>
function myFunction() {
    document.getElementById("mySelect").className = "updated";
}
</script>

<style>
.updated {
	background-color: #bcd9ff
}
</style>

</body>
</html>