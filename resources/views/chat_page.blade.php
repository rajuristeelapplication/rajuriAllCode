<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/@weavy/themes@11.1.0/dist/weavy-default.css" rel="stylesheet"
        crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/@weavy/dropin-js@11.1.0/dist/weavy-dropin.js" crossorigin="anonymous">
    </script>
</head>


<body>
    <div>
        <div id="messenger" style="height:600px;border:1px solid black;"></div>
    </div>

    <script>
        var weavy = new Weavy({
            url: "https://rajuristeels.weavy.io",
            jwt: () => "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJnZXQud2VhdnkuaW8iLCJzdWIiOjI0ODYsImRpciI6IlNhbmRib3giLCJnaXZlbl9uYW1lIjoiU2Fua2V0IiwiZmFtaWx5X25hbWUiOiJNdW5kYWRhIiwiZW1haWwiOiJjb250YWN0QHJhanVyaXN0ZWVscy5jb20iLCJleHAiOjE2NjA2NDU3MDl9.q9khyTSuyo-ij6CGXc8o4C8hncoBFpXjymVyISYtSWw"
        });

        var messenger = weavy.app({
            id: "messenger-demo",
            type: "messenger",
            container: "#messenger"
        });
    </script>

</body>

</html>
