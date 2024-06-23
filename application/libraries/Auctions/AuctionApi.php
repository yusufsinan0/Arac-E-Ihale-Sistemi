<?php

interface AuctionApi
{
    public function getAuctions();

    public function getAuction($id);

    public function getQuotesByAuctionId($id);

    public function getQuote($id);

    public function addQuoteToAuction($id, $amount);

    public function removeQuote($id);

    public function getFields();
}