<!DOCTYPE html>
<html>
<head>
    <title>분기별 막대 그래프</title>
    <style>
        .bar-chart {
            display: flex;
            
            width: 100%;
            height: 50px;
            background-color: aliceblue;
            /* overflow-x: scroll; */
        }

        .bar {
            display: flex; /* 수정: 내부 요소들을 가운데 정렬을 위해 display: flex; 추가 */
            justify-content: center; /* 수정: 가운데 정렬을 위해 justify-content 속성 추가 */
            align-items: center; /* 수정: 가운데 정렬을 위해 align-items 속성 추가 */
            flex-shrink: 0;

            width: 25%;
            height: 50px;
            
            text-align: center; /* 수정: 가운데 정렬을 위해 text-align 속성 추가 */
        }

        .completed {
            background-color: red;
            color: #fff;
        }

        .in-progress {
            background-color: antiquewhite;
            color: #000;
        }

        .disabled {
            background-color: gold;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="bar-chart">
        <div class="bar">1Q</div>
        <div class="bar">2Q</div>
        <div class="bar">3Q</div>
        <div class="bar">4Q</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            // 현재 날짜 구하기
            var now = new Date();
            var currentMonth = now.getMonth() + 1;
            currentMonth = 8;
            var currentQuarter = Math.ceil(currentMonth / 3);

            // 현재 분기에 진행중 클래스 추가
            $(".bar:nth-child(" + currentQuarter + ")").addClass("in-progress").text("진행중");

            // 현재 분기 이전에 완료 클래스 추가
            $(".bar:lt(" + (currentQuarter - 1) + ")").addClass("completed").text("완료");

            


            
        });
    </script>
</body>
</html>
