<table style="border:0">
  <!-- [ResultCode: {$result.resultCode}] -->
  {*
  <tr>
    <td>Result code:</td>
    <td>{$result.resultCode}</td>
  </tr>
  *}
  <tr>
    <td>Transaction reference:</td>
    <td>{$result.merchTxnRef}</td>
  </tr>
  <tr>
    <td>Transaction No:</td>
    <td>{$result.transactionNo}</td>
  </tr>
  <tr>
    <td>Receipt No:</td>
    <td>{$result.receiptNo}</td>
  </tr>
  <tr>
    <td>Authorization ID:</td>
    <td>{$result.authorizationID}</td>
  </tr>
  <tr>
    <td>Batch No:</td>
    <td>{$result.batchNo}</td>
  </tr>
  {if $result.failReason}
  <tr>
    <td>Fail Reason:</td>
    <td>{$result.failReason}</td>
  </tr>
  {/if}
</table>