<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="/">
  <html>
    <head>
    <style type="text/css">
    body {
      margin-left: 10;
      margin-right: 10;
      font:normal 80% Open Sans,sans-serif;
      background-color:#FFFFFF;
      color:#000000;
    }
    .a td { 
      background: #efefef;
    }
    .b td { 
      background: #fff;
    }
    th, td {
      text-align: left;
      vertical-align: top;
      padding: 5px;
      border: 1px solid #ccc;
    }

    th {
      font-weight:bold;
      background: #eee;
      color: black;
    }

    table, th, td {
      font-size:100%;
    }

    h2 {
      font-weight:bold;
      font-size:140%;
      margin-bottom: 5;
    }
    h3 {
      font-size:100%;
      font-weight:bold;
      background: #525D76;
      color: white;
      text-decoration: none;
      padding: 5px;
      margin-right: 2px;
      margin-left: 2px;
      margin-bottom: 0;
    }
    </style>
    </head>
  <body>
  <h2>phploc summary</h2>
  <table>
    <thead>
      <tr bgcolor="#9acd32">
        <th>Name</th>
        <th>Value</th>
      </tr>
    </thead>
    <xsl:for-each select="phploc/*">
    <tr>
      <th><xsl:value-of select="local-name()"/></th>
      <td><xsl:value-of select="."/></td>
    </tr>
    </xsl:for-each>
  </table>
  </body>
  </html>
</xsl:template>

</xsl:stylesheet> 