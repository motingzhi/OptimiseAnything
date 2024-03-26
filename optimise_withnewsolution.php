<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        #loadingContainer {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        }

        #loadingIcon {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #7EAB55;
        width: 50px;
        height: 50px;
        animation: spin 1s linear infinite;
        }

        @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
        }

        #loadingText {
        text-align: center;
        margin-top: 20px;
        }
    </style>
	
</head>
<body>

    <div id="background">
    
    <div style="display: flex; justify-content: space-between;">
        <h1>2. Optimise</h1>
        <form action="help.php#optimisation">
            <input type="submit" value="Help" class="button" id="help-button" style="color: white; background-color: #0173bc;"/>
        </form>
    </div>

    <p>Let AI suggest alternatives with you</p>
    <div id="dataDisplay"></div>


    <p><b>New alternative</b><p>

    <p class="parameter_1_mobo"></p>
    <p class="parameter_2_mobo"></p>
    <!-- <p class="generatedSolution"></p> -->


    <!-- 自己改的 -->
    <ul id="generatedSolution" style="list-style-type: none;"></ul>

    <!-- 自己改的 -->


    
    <div id="options" style="display: inline-block; margin: 0 auto;">
        <button class="button" id="evaluate-button" style="width: 40%;" onclick="evaluateSolution()">I want to evaluate this</button>
        <button class="button" id="skip-button" style="width: 40%;" onclick="newSolution()">Skip. I know it's not good</button>
    </div>
    <br>
    <div id="evaluate-solution" style="display: none;">
        <label for="solution_name">Solution name: </label>
        <input size="40" id = "solution_name" placeholder="Give a memorable name to this idea"><br><br>



        <!-- 原有的用来显示measurement的代码  -->

        <!-- <label for="obj1" class="objective_1_name"></label>
        <input size="30" type="text" id="obj1" name="obj1" placeholder="Enter measurement"><br>
        <label for="obj2" class="objective_2_name"></label>
        <input size="30" type="text" id="obj2" name="obj2" placeholder="Enter measurement"><br>
        <br> -->

        <!-- 原有的用来显示measurement的代码  -->



        <!-- 我新加的 -->

        <table id="measurement-table" class="measurement-table" width="100%">
            <!-- <thead>  
                <tr>  
                <th id="record-parameter-name" width="40%"> Name </th>   
                <th id="record-parameter-lower-bound"> Minimum </th>  
                <th id="record-parameter-upper-bound"> Maximum </th>  
                </tr>  
            </thead>   -->
            
            <tbody>
            <!-- <tr>
                <td contenteditable="true" class="record-data" id="display-measurement-name"></td>
                <td contenteditable="true" class="record-data" id="record-measurement"></td>
            </tr> -->
            <!-- <tr>
                <td contenteditable="true" class="record-data" id="display-measurement-name"></td>
                <td contenteditable="true" class="record-data" id="record-measurement"></td>
            </tr> -->
            <!-- <tr>
                <td contenteditable="true" class="record-data" id="record-parameter-name">Number of flight connections</td>
                <td contenteditable="true" class="record-data" id="record-parameter-lower-bound">0</td>
                <td contenteditable="true" class="record-data" id="record-parameter-upper-bound">3</td>
            </tr> -->
            </tbody>
        </table>




        <!-- 我新加的 -->





        <div id="form-options" style="display: inline-block; margin: 0 auto;">
            <button class="button" id="next-button" onclick="nextEvaluation()">Give me the next one</button>
            <button class="button" id="skip-button" onclick="refineSolution()">I want to refine this</button>
        </div>
    </div>
    <br>
    <div id="done-button" class="done-button" style="text-align: right;">
        <button class="button" id="done" onclick="finishSolutions()">I'm done</button>    
    </div>

    <div id="loadingContainer">
    <div id="loadingIcon"></div>
    <div id="loadingText">Loading...</div>
    </div>

    </div>

    <script>
        var parameterNames = localStorage.getItem("parameter-names").split(",");
        var parameterBounds = localStorage.getItem("parameter-bounds").split(",");
        var objectiveNames = localStorage.getItem("objective-names").split(",");
        var objectiveBounds = localStorage.getItem("objective-bounds").split(",");
        var objectiveMinMax = localStorage.getItem("objective-min-max").split(",");
        var goodSolutions = localStorage.getItem("good-solutions").split(",");
        var badSolutions = localStorage.getItem("bad-solutions").split(",");
        var solutionList = localStorage.getItem("solution-list").split(",");
        var savedSolutions = localStorage.getItem("saved-solutions").split(",");
        var savedObjectives = localStorage.getItem("saved-objectives").split(",");
        
        try {
        var objectiveMeasurements = localStorage.getItem("objective-Measurements").split(",");
        } catch (err) {
        // 如果发生异常，例如 "saved-objectives" 不存在，赋值一个空数组
        var objectiveMeasurements = [];
        }
        // 现在，objectiveMeasurements 包含 localStorage 中 "saved-objectives" 的值，如果不存在则为一个空数组


        
        var num_parameters = parameterNames.length

        try {var objectivesInput = localStorage.getItem("objectives-input").split(",");}
        catch(err) {}

        try {var solutionNameList = localStorage.getItem("solution-name-list").split(",");}
        catch(err) {}

        console.log("Current solutions: " + solutionList);
        console.log("Objectives input: " + objectivesInput);
        console.log("Bad solutions: " + badSolutions);
        console.log("Saved solutions: " + savedSolutions);
        console.log("Saved objectives: " + savedObjectives);
        console.log("Solution name list: " + solutionNameList);
        // catch(err) {}

        
	var DisplaySolutionText = 0;

	
	var displayDiv = document.getElementById("dataDisplay");
        displayDiv.innerHTML =  "Please evaluate at least 3 alternatives to proceed. You have evaulated " + parseInt(savedSolutions.length/parameterNames.length) + "<br>";


//////////////////自己加的 
////用 var = [] 定义一个数组，必须要有[]
        var generatedSolution = [];
      

///以下为了多parameter的情况：
        for (var i = 0; i<parameterNames.length; i++) {
            generatedSolution[i] = parameterNames[i] + " =  " + solutionList[solutionList.length-parameterNames.length+i];
        }

        console.log(generatedSolution);

        // 获取要填充数据的 <ul> 元素
        var generatedSolutionUI = document.getElementById("generatedSolution");

        // 循环遍历数组并将每个元素添加为列表项
        generatedSolution.forEach(function(element) {
        var listItem = document.createElement("li");
        listItem.textContent = element;
        generatedSolutionUI.appendChild(listItem);
        });
        
        // for (var i = 0; i<parameterNames.length; i++) {
        //     generatedSolutionUI[i].innerHTML = parameterNames[i] + " =  " + solutionList[solutionList.length-2];
        // }



//////////////////自己加的

        // var paras1 = document.getElementsByClassName("parameter_1_mobo");
        // var paras2 = document.getElementsByClassName("parameter_2_mobo");
        // console.log(parameterNamesLength);
        // var paras3 = document.getElementsByClassName("parameter_3_mobo");
        
        // for (i = 0; i < paras1.length; i++) {
        //     paras1[i].innerHTML = parameterNames[0] + " =  " + solutionList[solutionList.length-2];
        //     paras2[i].innerHTML = parameterNames[1] + " =  " + solutionList[solutionList.length-1];
        //     //  paras3[i].innerHTML = parameterNames[2] + " =  " + solutionList[solutionList.length-1];
        // }


        // paras1[0].innerHTML = parameterNames[0] + " =  " + solutionList[solutionList.length-2];
        // paras2[0].innerHTML = parameterNames[1] + " =  " + solutionList[solutionList.length-1];
            //  paras3[i].innerHTML = parameterNames[2] + " =  " + solutionList[solutionList.length-1];




        if (savedSolutions.length >= num_parameters*3) {
            // document.getElementById("done-button").style.opacity = 1;
            document.getElementById("done-button").style.display = 'block';
        }
        else {
            // document.getElementById("done-button").style.opacity = 0.5;
            document.getElementById("done-button").style.display = 'none';
        }

        if (savedSolutions.length < 2) {
            document.getElementById("solution_name").value = 'Solution 1';
        }
        else {
            document.getElementById("solution_name").value = 'Solution ' + Math.round(savedSolutions.length/num_parameters + 1);
        }
            
        // Individual solutions
        // document.getElementById("solution_1").innerHTML = solution[0];
        // document.getElementById("solution_2").innerHTML = solution[1];
        // document.getElementById("solution_3").innerHTML = solution[2];

        function newSolution() {
            callNewSolution = true;
            callNextEvaluation = false;
            callRefineSolution = false;
            // badSolutions.push(solutionList[solutionList.length-2], solutionList[solutionList.length-1])
            // Placeholders
            objectiveMeasurements = "";
            solutionName = "";

            //console.log("Sending AJAX request to server...");


            //localStorage.setItem("objective-measurements", objectiveMeasurements);




            $.ajax({
                url: "./cgi/newSolution_u.py",

                type: "post",
                datatype: "json",
                data: { 'parameter-names'    :String(parameterNames),
                        'parameter-bounds'   :String(parameterBounds),
                        'objective-names'    :String(objectiveNames), 
                        'objective-bounds'   :String(objectiveBounds),
                        'objective-min-max'  :String(objectiveMinMax),
                        
                        'good-solutions'     :String(goodSolutions),
                        'bad-solutions'      :String(badSolutions),
                        'current-solutions'  :String(solutionList),
                        'saved-solutions'    :String(savedSolutions),
                        'saved-objectives'    :String(savedObjectives),
                        
                        'new-solution'       :String(callNewSolution),
                        'next-evaluation'    :String(callNextEvaluation),
                        'refine-solution'    :String(callRefineSolution),
                        
                        'solution-name'      :String(solutionName),
                        'objective-measurements'        :String(objectiveMeasurements)},
                beforeSend: function() {
                // 显示 loading 动画和文字
                $('#loadingContainer').show();
                },
                success: function(result) {
                    submitReturned = true;
                    solutionList = result.solution;
                    objectivesInput = result.objectives;

                    badSolutions = result.bad_solutions;
                    savedSolutions = result.saved_solutions;
                    savedObjectives = result.saved_objectives;
                    console.log("Success-newSolution_Reply_list");
                    console.log(result.solution);
                    console.log(result.tester);
                    console.log(result.solution_normalised);
                    localStorage.setItem("solution-list", solutionList);
                    localStorage.setItem("objectives-input", objectivesInput);
                    localStorage.setItem("bad-solutions", badSolutions);
                    localStorage.setItem("saved-solutions", savedSolutions);
                    localStorage.setItem("saved-objectives", savedObjectives);
                    console.log("Success-newSolution_Reply_list_ends");
                    var url = "optimise_withnewsolution.php";
                    location.href = url;
		    $('#loadingContainer').hide();

            
                },
                error: function(result){
                    console.log(parameterBounds);
                    console.log("Error in finishing experiment: " + result.message);
                    console.log("Current solutions: " + solutionList);
                    console.log("Objectives input: " + objectivesInput);
                    console.log("Bad solutions: " + badSolutions);
                    console.log("Saved solutions: " + savedSolutions);
                    console.log("Saved objectives: " + savedObjectives);
                }
               
            });
        }

        function evaluateSolution() {


          ////////我加的  


        var x = document.getElementById('evaluate-solution');
        var y = document.getElementById('options')
            if (x.style.display == 'none') {
                x.style.display = 'block';
                y.style.display = 'none';
            }
            else {
                x.style.display = 'none';
                y.style.display = 'inline-block';
            }
        
           for (i = 0; i < objectiveNames.length; i++) {
                var htmlNewRow = ""
                htmlNewRow += "<tr>"
                htmlNewRow += "<td contenteditable='true' class='record-data' id='display-measurement-name'> " + objectiveNames[i]  +  " </td>"
                htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement'> " + "Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")"+ " </td>"// placeholder的效果怎么做
                // htmlNewRow += "<td contenteditable='true' class='record-data' id='record-measurement' placeholder='Enter measurement (" + objectiveBounds[2*i] + "-" + objectiveBounds[2*i+1]  + ")'> </td>"
                // htmlNewRow += "<td id='record-data-buttons'>"
                htmlNewRow += "</td></tr>"
                $("#measurement-table", window.document).append(htmlNewRow);
            }

 

          ////////我加的  

            // var obj1_name = document.getElementsByClassName("objective_1_name");
            // var obj2_name = document.getElementsByClassName("objective_2_name");

            // for (i = 0; i < paras1.length; i++) {
            //     obj1_name[i].innerHTML = objectiveNames[0] + " = ";
            //     obj2_name[i].innerHTML = objectiveNames[1] + " = ";
            // }
            
            // document.getElementById("obj1").placeholder = "Enter measurement ("+objectiveBounds[0]+"-"+objectiveBounds[1]+")";
            // document.getElementById("obj2").placeholder = "Enter measurement ("+objectiveBounds[2]+"-"+objectiveBounds[3]+")";


        }



        function nextEvaluation() {


            noError = true;

            callNewSolution = false;
            callNextEvaluation = true;
            callRefineSolution = false;

            var solutionName = document.getElementById("solution_name").value;


 

//////////////自己加的

            var tableParam = $("#measurement-table tbody");
                

            tableParam.find('tr').each(function(index) {
                var $paramCols = $(this).find("td");

                // 获取当前行的第二列数据
                var objElement = $paramCols.eq(1).text();

                // 将第二列数据填充到objectiveMeasurements对应的位置
                objectiveMeasurements[index] = objElement;
            });

            console.log("chatgpt",objectiveMeasurements);
            console.log("Solution name: " , solutionName);

//////////////自己加的

            for (let i = 0; i < objectiveMeasurements.length; i++) {
                var validObj1 = (!isNaN(parseFloat(objectiveMeasurements[i])) && isFinite(objectiveMeasurements[i])
                && parseFloat(objectiveMeasurements[i]) >= objectiveBounds[2*i] && parseFloat(objectiveMeasurements[i]) <= objectiveBounds[2*i+1]);
                if (validObj1 == false){
                    noError = false;
                    break
                }

            }



            // var validObj1 = (!isNaN(parseFloat(objElement[i])) && isFinite(objElement[i])
            //     && parseFloat(obj[i]) >= objectiveBounds[2i] && parseFloat(obj[i]) <= objectiveBounds[2i+1]);


            // ///          var validObj1 = (!isNaN(parseFloat(obj1)) && isFinite(obj1)
            // ////    && parseFloat(obj1) >= objectiveBounds[0] && parseFloat(obj1) <= objectiveBounds[1]);


            // if (validObj1 && validObj2) {
            //     noError = true;
            // }
            // else {
            //     noError = false;
            // }

            // if (/^[A-Za-z0-9]+$/.test(solutionName) == false){
            //     noError = false;
            // }

            if (noError) {
                // solutionName.push(solutionNameElement);
                localStorage.setItem("solution-name", solutionName);
                localStorage.setItem("objective-measurements", objectiveMeasurements);
                // localStorage.setItem("solution-list", solutionList);
                // localStorage.setItem("objectives-input", objectivesInput);
                // localStorage.setItem("bad-solutions", badSolutions);
                // localStorage.setItem("saved-solutions", savedSolutions);
                // localStorage.setItem("saved-objectives", savedObjectives);

                console.log("objectiveMeasurements",objectiveMeasurements)
                console.log("parameterNames",parameterNames)
                console.log("parameterBounds",parameterBounds)
                console.log("objectiveNames",objectiveNames)
                console.log("objectiveBounds",objectiveBounds)
                console.log("objectiveMinMax",objectiveMinMax)

                console.log("badSolutions",badSolutions)
                console.log("goodSolutions",goodSolutions)
                console.log("solutionList",solutionList)
                console.log("savedSolutions",savedSolutions)
                console.log("savedObjectives",savedObjectives)
                console.log("objectivesInput",objectivesInput)

    
                $.ajax({
                    url: "./cgi/next-evaluation.py",
                    type: "post",
                    datatype: "json",
                    data: { 'parameter-names'    :String(parameterNames),
                            'parameter-bounds'   :String(parameterBounds),
                            'objective-names'    :String(objectiveNames), 
                            'objective-bounds'   :String(objectiveBounds),
                            'objective-min-max'  :String(objectiveMinMax),

                            'good-solutions'     :String(goodSolutions),
                            'bad-solutions'      :String(badSolutions),
                            'current-solutions'  :String(solutionList),
                            'saved-solutions'    :String(savedSolutions),
                            'saved-objectives'   :String(savedObjectives),
                            'objectives-input'   :String(objectivesInput),

                            'new-solution'       :String(callNewSolution),
                            'next-evaluation'    :String(callNextEvaluation),
                            'refine-solution'    :String(callRefineSolution),

                            'solution-name'      :String(solutionName),
                            'solution-name-list'      :String(solutionNameList),
                            'objective-measurements'   :String(objectiveMeasurements)},
	            beforeSend: function() {
	                // 显示 loading 动画和文字
	                $('#loadingContainer').show();
	                },
                    success: function(result) {
                        submitReturned = true;
                        solutionList = result.solution;
                        objectivesInput = result.objectives;
                        badSolutions = result.bad_solutions;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        solutionNameList = result.solutionNameList;

			DisplaySolutionText = 1;
                        
                        localStorage.setItem("solution-list", solutionList);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);
                        localStorage.setItem("solution-name-list", solutionNameList);

                        console.log("Success-nextevaluation");
                        console.log(result.solution);
                        console.log(result.saved_objectives);

                        // console.log(result.test2);
                        console.log("Success-nextevaluation-reply-ends");

                        var url = "optimise_withnewsolution.php";
                        location.href = url;
   		        $('#loadingContainer').hide();

                    },
                    error: function(result){
                        console.log("Error in finishing experiment: " + result.message);
                        console.log(parameterBounds);
                    console.log("Current solutions: " + solutionList);
                    console.log("Objectives input: " + objectivesInput);
                    console.log("Bad solutions: " + badSolutions);
                    console.log("Saved solutions: " + savedSolutions);
                    console.log("Saved objectives: " + savedObjectives);
                    console.log("objectiveMeasurements",objectiveMeasurements)

                    }
                });
            }
            else {
                alert("Invalid entry");
            }  
        }

        function refineSolution() {
            noError = true;

            callNewSolution = false;
            callNextEvaluation = false;
            callRefineSolution = true;

            var solutionName = document.getElementById("solution_name").value;


            
            var tableParam = $("#measurement-table tbody");
                

                tableParam.find('tr').each(function(index) {
                    var $paramCols = $(this).find("td");
    
                    // 获取当前行的第二列数据
                    var objElement = $paramCols.eq(1).text();
    
                    // 将第二列数据填充到objectiveMeasurements对应的位置
                    objectiveMeasurements[index] = objElement;
                });
    
                console.log("chatgpt2",objectiveMeasurements);
    
    //////////////自己加的
    
                for (let i = 0; i < objectiveMeasurements.length; i++) {
                    var validObj1 = (!isNaN(parseFloat(objectiveMeasurements[i])) && isFinite(objectiveMeasurements[i])
                    && parseFloat(objectiveMeasurements[i]) >= objectiveBounds[2*i] && parseFloat(objectiveMeasurements[i]) <= objectiveBounds[2*i+1]);
                    if (validObj1 == false){
                        noError = false;
                        break
                    }
    
                }
    
    

            // if (/^[A-Za-z0-9]+$/.test(solutionName) == false){
            //     noError = false;
            // }

            if (noError) {
                localStorage.setItem("solution-name", solutionName);
                localStorage.setItem("objective-measurements", objectiveMeasurements);
                console.log("objectiveMeasurements",objectiveMeasurements)
                console.log("parameterNames",parameterNames)
                console.log("parameterBounds",parameterBounds)
                console.log("objectiveNames",objectiveNames)
                console.log("objectiveBounds",objectiveBounds)
                console.log("objectiveMinMax",objectiveMinMax)
                console.log("badSolutions",badSolutions)
                console.log("goodSolutions",goodSolutions)
                console.log("solutionList",solutionList)
                console.log("savedSolutions",savedSolutions)
                console.log("savedObjectives",savedObjectives)
                console.log("objectivesInput",objectivesInput)
    
                $.ajax({
                    url: "./cgi/refine-solution.py",
                    type: "post",
                    datatype: "json",
                    data: { 'parameter-names'    :String(parameterNames),
                            'parameter-bounds'   :String(parameterBounds),
                            'objective-names'    :String(objectiveNames), 
                            'objective-bounds'   :String(objectiveBounds),
                            'objective-min-max'  :String(objectiveMinMax),

                            'good-solutions'     :String(goodSolutions),
                            'bad-solutions'      :String(badSolutions),
                            'current-solutions'  :String(solutionList),
                            'saved-solutions'    :String(savedSolutions),
                            'saved-objectives'   :String(savedObjectives),
                            'objectives-input'   :String(objectivesInput),

                            'new-solution'       :String(callNewSolution),
                            'next-evaluation'    :String(callNextEvaluation),
                            'refine-solution'    :String(callRefineSolution),

                            'solution-name'      :String(solutionName),
                            'solution-name-list'      :String(solutionNameList),
                            'objective-measurements'   :String(objectiveMeasurements)},

	            beforeSend: function() {
	                // 显示 loading 动画和文字
	                $('#loadingContainer').show();
	                },
                    success: function(result) {
                        submitReturned = true;
                        solutionList = result.solution
                        objectivesInput = result.objectives
                        badSolutions = result.bad_solutions;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        solutionNameList = result.solutionNameList;


                        new_x = result.new_x
                        console.log(new_x)
                        console.log(result.solution)
                        console.log(result.objectives)
                        localStorage.setItem("solution-list", solutionList);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);
                        localStorage.setItem("solution-name-list", solutionNameList);


                        console.log("Success-refineevaluation");
                        var url = "optimise_withnewsolution.php";
                        location.href = url;
			$('#loadingContainer').hide();
                    },
                    error: function(result){
                        console.log("Error in finishing experiment: " + result.message);
                    }
                });
            }
            else {
                alert("Invalid entry");
            }  
        }


        function finishSolutions() {
            noError = true;
            
            if (savedSolutions.length/num_parameters < 3) {
                noError = false;
            }

            if (noError) {
                $.ajax({
                    url: "./cgi/finish-solutions.py",
                    type: "post",
                    datatype: "json",
                    data: { 'parameter-names'    :String(parameterNames),
                            'parameter-bounds'   :String(parameterBounds),
                            'objective-names'    :String(objectiveNames), 
                            'objective-bounds'   :String(objectiveBounds),
                            'objective-min-max'  :String(objectiveMinMax),
                            'solution-name-list'      :String(solutionNameList),


                            'good-solutions'     :String(goodSolutions),
                            'bad-solutions'      :String(badSolutions),
                            'current-solutions'  :String(solutionList),
                            'saved-solutions'    :String(savedSolutions),
                            'saved-objectives'   :String(savedObjectives),
                            'objectives-input'   :String(objectivesInput)},

	            beforeSend: function() {
	                // 显示 loading 动画和文字
	                $('#loadingContainer').show();
	                },
                    success: function(result) {
                        submitReturned = true;
                        // solutionList = result.solution;
                        // badSolutions = result.bad_solutions;
                        objectivesInput = result.objectives;
                        savedSolutions = result.saved_solutions;
                        savedObjectives = result.saved_objectives;
                        objectivesNormalised = result.objectives_normalised;
                        // bestSolutions = result.best_solutions;
                        // solutionNameList = result.solutionNameList;
                        BestSolutionIndex = result.BestSolutionIndex;
			// console.log(solutionNameIndex);
                        console.log(objectivesInput);
                        console.log(savedSolutions);
                        console.log(objectivesNormalised);
                        // console.log(bestSolutions)
                        // localStorage.setItem("solution-list", solutionList);
                        // localStorage.setItem("bad-solutions", badSolutions);
                        localStorage.setItem("objectives-input", objectivesInput);
                        localStorage.setItem("saved-solutions", savedSolutions);
                        localStorage.setItem("saved-objectives", savedObjectives);
                        localStorage.setItem("objectives-normalised", objectivesNormalised);
                        // localStorage.setItem("best-solutions", bestSolutions);
                        localStorage.setItem("solution-name-list", solutionNameList);
                        localStorage.setItem("BestSolutionIndex", BestSolutionIndex);
                        console.log("solutionNameList",solutionNameList);
                        console.log("Success");
                        var url = "results.php";
                        location.href = url;
			$('#loadingContainer').hide();
                    },
                    error: function(result){
                        console.log("Error in finishing experiment: " + result.message);
                    }
                });
            }
            else {
                alert("Please ensure you have evaluated at least 3 solutions");
            }  
        }

    </script>
</body>
</html>
