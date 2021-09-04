<p>GET /get-open-games<p>
<p>Description: Get All open games<p>
<hr>
<p>GET /get-over-games<p>
<p>Description: Get All over games<p>
<hr>
<p>POST /new-game<p>
<p>{ nickname:"string required" }</p>
<p>Description: Create new game and add to game this user<p>
<hr>
<p>POST /connect-to-game<p>
<p>{ nickname:"string required",<br>game_id:"integer required" }</p>
<p>Description: Connect to game this user<p>
<hr>
<p>POST /choose-gesture<p>
<p>{ nickname:"string required",<br>
game_id:"integer required",<br>
gesture:"string in[rock,paper,scissors] required" }</p>
<p>Description: Choose gesture for this user<p>
<hr>
<p>GET /get-game<p>
<p>{ game_id:"integer required" }</p>
<p>Description: Get game info by id<p>
