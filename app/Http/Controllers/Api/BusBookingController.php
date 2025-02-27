<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Classes\WriteToFile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ApiHelper;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BusBookingController extends Controller
{

  public function test()
    {
        return response()->json(['message' => 'hello!']);
    }

    // public function __construct()
    // {
    //     $this->logPath = base_path(WriteToFile::$log_file_location[WriteToFile::BOOKING_SAJYATRA_BUS]);
    // }

    /**
     * Get BUS city  details
     * @param string source_city
     * @param string source_code
     * @param string destination_city
     * @param string destination_code
     * @param date depart_date
     */
    public function BusSearchList(Request $request)
    {
        try {
            // Validation
            $validator = Validator::make($request->all(), [
                'source_city' => 'required|string',
                'source_code' => 'required|string',
                'destination_city' => 'required|string',
                'destination_code' => 'required|string',
                'depart_date' => 'required|date',
            ], [
                'source_city.required' => 'Source city is required',
                'source_code.required' => 'Source code is required',
                'destination_city.required' => 'Destination city is required',
                'destination_code.required' => 'Destination code is required',
                'depart_date.required' => 'Departure date is required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
            }

            $searchData = $request->only(['source_city', 'source_code', 'destination_city', 'destination_code', 'depart_date']);

            $apiHelper = new ApiHelper();
            $responseData = $apiHelper->BusSearchApiCall($searchData);

            if ($responseData) {
                return response()->json(['status' => true, 'data' => $responseData], Response::HTTP_OK);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => $responseData], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            WriteToFile::logMessage($this->logPath, 'Error in BusSearchList: ' . $th->getMessage());
            return response()->json(['status' => false, 'message' => env('APP_DEBUG') ? $th->getMessage() : 'Something went wrong.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Get BUS Seat layout  details
     * @param string TraceId
     * @param string ResultIndex
     */
    public function BusSeatLayout(Request $request)
    {
         try {
            $validator = Validator::make($request->all(), [
                'TraceId' => 'required|string',
                'ResultIndex' => 'required|string',
            ], [
                'TraceId.required' => 'trace id  is required',
                'ResultIndex.required' => 'result index is required',
            ]);

            if (!empty($validator->errors()->messages())) {
                foreach ($validator->errors()->messages() as $key => $errorMessage) {
                    return response()->json(['status' => false, 'message' => $errorMessage[0]], Response::HTTP_BAD_REQUEST);
                }
            }

            $searchData = $request->only([
                'TraceId',
                'ResultIndex',
            ]);

            $apiHelper = new ApiHelper();
            $responseData = $apiHelper->BusSeatLayoutApi($searchData);
            if ($responseData) {
                return response()->json(['status' => true, 'data' => $responseData], Response::HTTP_OK);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => $responseData], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            WriteToFile::logMessage($this->logPath, 'Error GetBusBooking ' . $th->getMessage());
            return response()->json(['status' => false, 'message' => env('APP_DEBUG') ? $th->getMessage() : 'Something went wrong.'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get BUS Seat Boarding point  details
     * @param string TraceId
     * @param string ResultIndex
     */
    public function BusBoradingPoint(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'TraceId' => 'required|string',
                'ResultIndex' => 'required|string',
            ], [
                'TraceId.required' => 'trace id  is required',
                'ResultIndex.required' => 'result index is required',
            ]);

            if (!empty($validator->errors()->messages())) {
                foreach ($validator->errors()->messages() as $key => $errorMessage) {
                    return response()->json(['status' => false, 'message' => $errorMessage[0]], Response::HTTP_BAD_REQUEST);
                }
            }

            $searchData = $request->only([
                'TraceId',
                'ResultIndex',
            ]);

            $apiHelper = new ApiHelper();
            $responseData = $apiHelper->BusBoardingPointApi($searchData);
            if ($responseData) {
                return response()->json(['status' => true, 'data' => $responseData], Response::HTTP_OK);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => $responseData], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            WriteToFile::logMessage($this->logPath, 'Error GetBusBooking ' . $th->getMessage());
            return response()->json(['status' => false, 'message' => env('APP_DEBUG') ? $th->getMessage() : 'Something went wrong.'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get BUS Seat block  details
     * @param string TraceId
     * @param string ResultIndex
     * @param integer BoardingPointId
     * @param integer DroppingPointId
     * @param string RefID
     * @param array Passenger

     */
    public function BusSeatBlock(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ResultIndex' => 'required|integer',
                'TraceId' => 'required|integer',
                'BoardingPointId' => 'required',
                'DroppingPointId' => 'required',
                'RefID' => 'required|string',
                'Passenger' => 'required|array',
                'Passenger.*.LeadPassenger' => 'required|boolean',
                'Passenger.*.Title' => 'nullable|string',
                'Passenger.*.FirstName' => 'required|string',
                'Passenger.*.LastName' => 'required|string',
                'Passenger.*.Email' => 'required',
                'Passenger.*.Phoneno' => 'required|string',
                'Passenger.*.Gender' => 'required',
                'Passenger.*.IdType' => 'nullable|string',
                'Passenger.*.IdNumber' => 'nullable|string',
                'Passenger.*.Address' => 'required|string',
                'Passenger.*.Age' => 'required|integer',
                'Passenger.*.Seat' => 'required|array',
                'Passenger.*.Seat.ColumnNo' => 'required',
                'Passenger.*.Seat.Height' => 'required|integer',
                'Passenger.*.Seat.IsLadiesSeat' => 'required|boolean',
                'Passenger.*.Seat.IsMalesSeat' => 'required|boolean',
                'Passenger.*.Seat.IsUpper' => 'required|boolean',
                'Passenger.*.Seat.RowNo' => 'required',
                'Passenger.*.Seat.SeatFare' => 'required',
                'Passenger.*.Seat.SeatIndex' => 'required',
                'Passenger.*.Seat.SeatName' => 'required',
                'Passenger.*.Seat.SeatStatus' => 'required',
                'Passenger.*.Seat.SeatType' => 'required',
                'Passenger.*.Seat.Width' => 'required',
                'Passenger.*.Seat.Price' => 'required|array',
                'Passenger.*.Seat.Price.CurrencyCode' => 'required',
                'Passenger.*.Seat.Price.BasePrice' => 'required',
                'Passenger.*.Seat.Price.Tax' => 'required',
                'Passenger.*.Seat.Price.OtherCharges' => 'required',
                'Passenger.*.Seat.Price.Discount' => 'required',
                'Passenger.*.Seat.Price.PublishedPrice' => 'required',
                'Passenger.*.Seat.Price.PublishedPriceRoundedOff' => 'required',
                'Passenger.*.Seat.Price.OfferedPrice' => 'required',
                'Passenger.*.Seat.Price.OfferedPriceRoundedOff' => 'required',
                'Passenger.*.Seat.Price.AgentCommission' => 'required',
                'Passenger.*.Seat.Price.AgentMarkUp' => 'required',
                'Passenger.*.Seat.Price.TDS' => 'required',
                'Passenger.*.Seat.Price.GST' => 'required|array',
                'Passenger.*.Seat.Price.GST.CGSTAmount' => 'required',
                'Passenger.*.Seat.Price.GST.CGSTRate' => 'required',
                'Passenger.*.Seat.Price.GST.CessAmount' => 'required',
                'Passenger.*.Seat.Price.GST.CessRate' => 'required',
                'Passenger.*.Seat.Price.GST.IGSTAmount' => 'required',
                'Passenger.*.Seat.Price.GST.IGSTRate' => 'required',
                'Passenger.*.Seat.Price.GST.SGSTAmount' => 'required',
                'Passenger.*.Seat.Price.GST.SGSTRate' => 'required',
                'Passenger.*.Seat.Price.GST.TaxableAmount' => 'required',
            ]);

            if (!empty($validator->errors()->messages())) {
                foreach ($validator->errors()->messages() as $key => $errorMessage) {
                    return response()->json(['status' => false, 'message' => $errorMessage[0]], Response::HTTP_BAD_REQUEST);
                }
            }
            $searchData = $request->only([
                "ResultIndex",
                "TraceId",
                "RefID",
                "BoardingPointId",
                "DroppingPointId",
                "Passenger",
            ]);

            $apiHelper = new ApiHelper();
            $responseData = $apiHelper->BusSeatBlockApi($searchData);

            if ($responseData) {
                return response()->json(['status' => true, 'data' => $responseData], Response::HTTP_OK);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => $responseData], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            WriteToFile::logMessage($this->logPath, 'Error GetBusBooking ' . $th->getMessage());
            return response()->json(['status' => false, 'message' => env('APP_DEBUG') ? $th->getMessage() : 'Something went wrong.'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get BUS Seat book  details
     * @param string TraceId
     * @param string ResultIndex
     * @param integer BoardingPointId
     * @param integer DroppingPointId
     * @param string RefID
     * @param array Passenger

     */
    public function BusSeatBook(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ResultIndex' => 'required|integer',
                'TraceId' => 'required|integer',
                'BoardingPointId' => 'required|integer',
                'DroppingPointId' => 'required|integer',
                'RefID' => 'required|string',
                'Passenger' => 'required|array',
                'Passenger.*.LeadPassenger' => 'required|boolean',
                'Passenger.*.FirstName' => 'required|string',
                'Passenger.*.LastName' => 'required|string',
                'Passenger.*.Email' => 'required|email',
                'Passenger.*.Phoneno' => 'required|string',
                'Passenger.*.Gender' => 'required',
                'Passenger.*.IdType' => 'nullable|string',
                'Passenger.*.IdNumber' => 'nullable|string',
                'Passenger.*.Address' => 'required',
                'Passenger.*.Age' => 'required|integer',
                'Passenger.*.Seat' => 'required|array',
                'Passenger.*.Seat.ColumnNo' => 'required',
                'Passenger.*.Seat.Height' => 'required',
                'Passenger.*.Seat.IsLadiesSeat' => 'required',
                'Passenger.*.Seat.IsMalesSeat' => 'required',
                'Passenger.*.Seat.IsUpper' => 'required',
                'Passenger.*.Seat.RowNo' => 'required',
                'Passenger.*.Seat.SeatFare' => 'required',
                'Passenger.*.Seat.SeatIndex' => 'required',
                'Passenger.*.Seat.SeatName' => 'required',
                'Passenger.*.Seat.SeatStatus' => 'required',
                'Passenger.*.Seat.SeatType' => 'required',
                'Passenger.*.Seat.Width' => 'required',
                'Passenger.*.Seat.Price' => 'required|array',
                'Passenger.*.Seat.Price.CurrencyCode' => 'required',
                'Passenger.*.Seat.Price.BasePrice' => 'required',
                'Passenger.*.Seat.Price.Tax' => 'required',
                'Passenger.*.Seat.Price.OtherCharges' => 'required',
                'Passenger.*.Seat.Price.Discount' => 'required',
                'Passenger.*.Seat.Price.PublishedPrice' => 'required',
                'Passenger.*.Seat.Price.PublishedPriceRoundedOff' => 'required',
                'Passenger.*.Seat.Price.OfferedPrice' => 'required',
                'Passenger.*.Seat.Price.OfferedPriceRoundedOff' => 'required',
                'Passenger.*.Seat.Price.AgentCommission' => 'required',
                'Passenger.*.Seat.Price.AgentMarkUp' => 'required',
                'Passenger.*.Seat.Price.TDS' => 'required',
                'Passenger.*.Seat.Price.GST' => 'required|array',
                'Passenger.*.Seat.Price.GST.CGSTAmount' => 'required',
                'Passenger.*.Seat.Price.GST.CGSTRate' => 'required',
                'Passenger.*.Seat.Price.GST.CessAmount' => 'required',
                'Passenger.*.Seat.Price.GST.CessRate' => 'required',
                'Passenger.*.Seat.Price.GST.IGSTAmount' => 'required',
                'Passenger.*.Seat.Price.GST.IGSTRate' => 'required',
                'Passenger.*.Seat.Price.GST.SGSTAmount' => 'required',
                'Passenger.*.Seat.Price.GST.SGSTRate' => 'required',
                'Passenger.*.Seat.Price.GST.TaxableAmount' => 'required',
            ]);

            if (!empty($validator->errors()->messages())) {
                foreach ($validator->errors()->messages() as $key => $errorMessage) {
                    return response()->json(['status' => false, 'message' => $errorMessage[0]], Response::HTTP_BAD_REQUEST);
                }
            }
            $searchData = $request->only([
                "ResultIndex",
                "TraceId",
                "RefID",
                "BoardingPointId",
                "DroppingPointId",
                "Passenger",
            ]);

            $apiHelper = new ApiHelper();
            $responseData = $apiHelper->BusSeatBookApi($searchData);
            if ($responseData) {
                return response()->json(['status' => true, 'data' => $responseData], Response::HTTP_OK);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => $responseData], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            WriteToFile::logMessage($this->logPath, 'Error GetBusBooking ' . $th->getMessage());
            return response()->json(['status' => false, 'message' => env('APP_DEBUG') ? $th->getMessage() : 'Something went wrong.'], Response::HTTP_BAD_REQUEST);
        }
    }


    /**
     * Get BUS Seat block  details
     * @param string BusId
     * @param string SeatId
     */
    public function BusSeatCancel(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                "BusId" =>  "required",
                "Remarks" => 'nullable',
                'SeatId' => 'required|string',
            ], [
                'BusId.required' => 'bus id field is required',
                'SeatId.required' => 'seat id  field is required'
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()], Response::HTTP_BAD_REQUEST);
            }

            $searchData = $request->only(['BusId', 'Remarks', 'SeatId',]);

            $apiHelper = new ApiHelper();
            $responseData = $apiHelper->BusSeatCancelApi($searchData);
            if ($responseData) {
                return response()->json(['status' => true, 'data' => $responseData], Response::HTTP_OK);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong', 'data' => $responseData], Response::HTTP_NOT_FOUND);
            }
        } catch (\Throwable $th) {
            WriteToFile::logMessage($this->logPath, 'Error GetBusBooking ' . $th->getMessage());
            return response()->json(['status' => false, 'message' => env('APP_DEBUG') ? $th->getMessage() : 'Something went wrong.'], Response::HTTP_BAD_REQUEST);
        }
    }
}
