<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<script>
    var stopTimer = true;
    var taskId;
    $(document).ready(function(){

        $(".dial").knob({
            'release' : function (v) {
                var taskId = $(this.i).attr("id").substr(5);
                var taskStatus = v;
                $.ajax({
                    url: "<?php echo base_url() . 'tasks/update-status';?>",
                    type: "post",
                    dataType: 'json',
                    data: {"task_id": taskId, "status" : taskStatus},
                    cache: false,
                    success: function (json) {

                        if(taskStatus=="100")
                        {
                            var timerElement = "#countup" + taskId;
                            $(timerElement).parents("tr").hide();
                            getFinished(<?php echo $project->id;?>);
                        }
                        var message = json.message;
                        //console.log(message);
                    },
                    error: function (xhr, desc, err) {
                        console.log("not ok");
                        console.log(xhr);
                        var message = desc + "\nError:" + err;
                        console.log(message);
                    }
                }); // end ajax call
            }
        });
        
        getFinished(<?php echo $project->id;?>);
        
        $(".start").on("click",function(e){
            $(".start").hide();
            $(this).parents("tr").addClass("warning");
            $(".back-projects, .add-task, .edit-task, a.not-finished").hide("slow");
            taskId = $(this).data("target");

            $.ajax({
                url: "<?php echo base_url() . 'tasks/task-history/';?>",
                type: "post",
                dataType: 'json',
                data: {"task_id": taskId},
                cache: false,
                success: function (json) {
                    var taskHistory = $("#task-history");
                    $(".history", taskHistory).html(json.history);
                    $("input[type=hidden]", taskHistory).val(taskId);
                    $(taskHistory).show("slow");
                },
                error: function (xhr, desc, err) {
                    console.log("not ok");
                    console.log(xhr);
                    var message = desc + "\nError:" + err;
                    console.log(message);
                }
            }); // end ajax call

            var timerElement = "#countup" + taskId;
            var timer = $(timerElement);
            $(timer).addClass("active");
            $(".stop",timer).show();
            $(".finished",timer).show();
            var seconds = parseInt($(".seconds",timer).text());
            var minutes = parseInt($(".minutes",timer).text());
            var hours = parseInt($(".hours",timer).text());
            var days = parseInt($(".days",timer).text());
            stopTimer = false;
            var loadTime = new Date();
            loadTime.setDate(loadTime.getDate()-days);
            loadTime.setHours(loadTime.getHours()-hours);
            loadTime.setMinutes(loadTime.getMinutes()-minutes);
            loadTime.setSeconds(loadTime.getSeconds()-seconds);
            upTime(loadTime,timerElement);
            e.preventDefault();
        });

        $("#add-comment").on("submit",function(e) {
            var comment = $("textarea",this).val();
            var timerElement = "#countup" + taskId;
            var timer = $(timerElement);
            var seconds = parseInt($(".seconds",timer).text());
            var minutes = parseInt($(".minutes",timer).text());
            var hours = parseInt($(".hours",timer).text());
            var days = parseInt($(".days",timer).text());
            var timeSpent = seconds + (minutes*60) + (hours*3600) + (days*86400);
            var taskStatus = $("#knob_" + taskId).val();
            var taskHistory = $("#task-history");

            $.ajax({
                url: "<?php echo base_url() . 'tasks/add-history/';?>",
                type: "post",
                dataType: 'json',
                data: {"task_id" : taskId, "time_spent" : timeSpent, "status" : taskStatus, "comment" : comment},
                cache: false,
                success: function (json) {
                    $("#add-comment textarea").val('');
                    $(".history", taskHistory).html(json.history);
                },
                error: function (xhr, desc, err) {
                    console.log("not ok");
                    console.log(xhr);
                    var message = desc + "\nError:" + err;
                    console.log(message);
                }
            }); // end ajax call
            e.preventDefault();
        });
        
        $(".stop, .finished").on("click",function(e){
            taskId =  $(this).data("target");
            var timerElement = "#countup" + taskId;
            var timer = $(timerElement);
            $(timer).removeClass("active");
            var days = parseInt($(".days",timer).text());
            var hours = parseInt($(".hours",timer).text());
            var minutes = parseInt($(".minutes",timer).text());
            var seconds = parseInt($(".seconds",timer).text());
            var timeSpent = seconds + (minutes*60) + (hours*3600) + (days*86400);
            var taskStatus = ($(this).hasClass("finished") ? '100' : $("#knob_" + taskId).val());

            $.ajax({
                url: "<?php echo base_url() . 'tasks/update-time';?>",
                type: "post",
                dataType: 'json',
                data: {"task_id": taskId, "time_spent": timeSpent, "status" : taskStatus},
                cache: false,
                success: function (json) {
                    var message = json.message;
                    //console.log(message);
                },
                error: function (xhr, desc, err) {
                    console.log("not ok");
                    console.log(xhr);
                    var message = desc + "\nError:" + err;
                    console.log(message);
                }
            }); // end ajax call


            if($(this).hasClass("finished"))
            {
                $(timerElement).parents("tr").hide();
                getFinished(<?php echo $project->id;?>);
            }
            $(".stop, .finished").hide();
            $(".start").show();
            $(this).parents("tr").removeClass("warning");
            $(".add-task, .back-projects, .edit-task, a.not-finished").slideDown("slow");
            var taskHistory = $("#task-history");
            $(".history", taskHistory).html('');
            $(taskHistory).slideUp("slow");
            stopTimer = true;
            taskId = 0;
            e.preventDefault();
        });
        
        $(document).on("click",".details",function(e){
            $(this).toggleClass("active");
            var taskId = $(this).data("target");
            $(this).parents("td").find(".details-show").fadeToggle();
            //console.log(container.html());
            e.preventDefault();
        });
    });

    function upTime(countFrom,timerElement) {
        if(stopTimer==false){
            now = new Date();
            difference = (now-countFrom);
            days=Math.floor(difference/(60*60*1000*24)*1);
            hours=Math.floor((difference%(60*60*1000*24))/(60*60*1000)*1);
            mins=Math.floor(((difference%(60*60*1000*24))%(60*60*1000))/(60*1000)*1);
            secs=Math.floor((((difference%(60*60*1000*24))%(60*60*1000))%(60*1000))/1000*1);

            var timer = $(timerElement);
            $(".days",timer).text(days);
            $(".hours",timer).text(hours<10 ? ("0" + hours) : hours);
            $(".minutes",timer).text(mins<10 ? ("0" + mins) : mins);
            $(".seconds",timer).text(secs<10 ? ("0" + secs) : secs);
            clearTimeout(upTime.to);
            upTime.to=setTimeout(function(){ upTime(countFrom,timerElement); },1000);
        }


    }
    
    function getFinished(projectId) {
        $.ajax({
            url: "<?php echo base_url() . 'tasks/get-finished-tasks/';?>" + projectId,
            type: "get",
            data: {},
            cache: false,
            success: function (data) {
                $(".finished-tasks").html(data);
            },
            error: function (xhr, desc, err) {
                console.log("not ok");
                console.log(xhr);
                var message = desc + "\nError:" + err;
                console.log(message);
            }
        }); // end ajax call
    }

    window.onbeforeunload = function(){
        if(stopTimer===false)
        {
            var timerElement = "#countup" + taskId;
            var timer = $(timerElement);
            var days = parseInt($(".days",timer).text());
            var hours = parseInt($(".hours",timer).text());
            var minutes = parseInt($(".minutes",timer).text());
            var seconds = parseInt($(".seconds",timer).text());
            var timeSpent = seconds + (minutes*60) + (hours*3600) + (days*86400);
            var finished = $("#knob_" + taskId).val();

            $.ajax({
                url: "<?php echo base_url() . 'tasks/update-time';?>",
                type: "post",
                dataType: 'json',
                data: {"task_id": taskId, "time_spent": timeSpent, "finished" : finished},
                cache: false,
                success: function (json) {
                    var message = json.message;
                    //console.log(message);
                },
                error: function (xhr, desc, err) {
                    console.log("not ok");
                    console.log(xhr);
                    var message = desc + "\nError:" + err;
                    console.log(message);
                }
            }); // end ajax call
        }
        console.log(stopTimer);
        return null;
    }
</script>