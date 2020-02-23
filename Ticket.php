<?php

class Ticket{
  private $conn;
  private $id;
  private $ticketNo;
  private $email;
  private $name;
  private $phone;
  private $location;
  private $availTime;
  private $availDate;
  private $description;
  private $status;

  public function __construct($conn, $data){
    $this->conn = $conn;
    $this->id = $data['id'];
    //$this->id = $id;

    if(!is_array($data)) {

      $result = mysqli_query($conn, "SELECT * FROM support_tickets WHERE ticketNo='$data'");
      if(!$result) die($conn->error);

      $data = mysqli_fetch_array($result);
    }

    $this->conn = $conn;
    $this->id = $data['id'];
    $this->name = $data['name'];
    $this->email = $data['email'];

    $result = mysqli_query($this->conn, "SELECT * FROM support_tickets WHERE ticketNo='$this->id'");
    if(!$result) die($conn->error);

    $this->mysqliData = mysqli_fetch_array($result);
    $this->ticketno = $this->mysqliData['ticketno'];
    $this->email = $this->mysqliData['email'];
    $this->name = $this->mysqliData['name'];
    $this->phone = $this->mysqliData['phone'];
    $this->location = $this->mysqliData['location'];
    $this->availtime = $this->mysqliData['availtime'];
    $this->availdate = $this->mysqliData['availdate'];
    $this->description = $this->mysqliData['description'];
    $this->status = $this->mysqliData['status'];
  }

  //Get ID of ticket
  public function getId() {
    return $this->id;
  }

  //Get the ticket IDs of this ticketNo
  public function getTicketNo() {

    $query = mysqli_query($this->conn, "SELECT ticketNo FROM support_tickets WHERE ticketNo='$this->id'");

    $array = array();

    //Autoincrements to assign ticketNos to tickets. Stored as an array
    while($row = mysqli_fetch_array($query)) {
      array_push($array, $row['ticketNo']);
    }

    return $array;

  }

  //Get email of person who filed ticket
  public function getEmail() {
    return $this->email;
  }

  //Get name of person who filed ticket
  public function getName() {
    return $this->name;
  }

  //Get phone of person who filed ticket
  public function getPhone() {
    return $this->owner;
  }

  //Get location of person who filed ticket
  public function getLocation() {
    return $this->location;
  }

  //Get available times of person who filed ticket
  public function getAvailTime() {
    return $this->availTime;
  }

  //Get available dates of person who filed ticket
  public function getAvailDate() {
    return $this->availDate;
  }

  //Get description of issue from ticket
  public function getDescription() {
    return $this->description;
  }

  //Get status of ticket (open, in progress, closed)
  public function getStatus() {
    return $this->status;
  }


}

?>
